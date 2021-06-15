<?php

namespace EmilMassey\Payum\Tpay\Tests;

use EmilMassey\Payum\Tpay\TpayGatewayFactory;
use Payum\Core\Exception\LogicException;
use PHPUnit\Framework\TestCase;

class TestTpayGatewayFactory extends TestCase
{
    public function testConfigHasDefaultOptions()
    {
        $factory = new TpayGatewayFactory();
        $config = $factory->createConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('payum.default_options', $config);

        $this->assertEquals(
            [
                'language' => 'en',
                'sandbox' => true,
            ],
            $config['payum.default_options']
        );
    }

    public function testConfigNameAndTitle()
    {
        $factory = new TpayGatewayFactory();
        $config = $factory->createConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('payum.factory_name', $config);
        $this->assertArrayHasKey('payum.factory_title', $config);
        $this->assertSame('tpay', $config['payum.factory_name']);
        $this->assertSame('TPay', $config['payum.factory_title']);
    }

    public function testThrowIfMissingRequiredOptionsWhenSandboxNotEnabled()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The merchant_id, secret, api_key, api_password fields are required.');

        $factory = new TpayGatewayFactory();
        $factory->create([
            'sandbox' => false,
        ]);
    }

    public function testThrowIfAnyRequiredOptionEmptyWhenSandboxNotEnabled()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The api_password fields are required.');

        $factory = new TpayGatewayFactory();
        $factory->create([
            'sandbox' => false,
            'merchant_id' => '1234',
            'secret' => 's3cr3t',
            'api_key' => 'asdfgh',
            'api_password' => '',
        ]);
    }

    /** @doesNotPerformAssertions */
    public function testCanOmitRequiredOptionsWhenSandboxEnabled()
    {
        $factory = new TpayGatewayFactory();
        $factory->create();
    }

    public function testAddDefaultConfigPassedInConstructorWhileCreatingGatewayConfig()
    {
        $factory = new TpayGatewayFactory(array(
            'foo' => 'fooVal',
            'bar' => 'barVal',
        ));

        $config = $factory->createConfig();

        $this->assertIsArray($config);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('fooVal', $config['foo']);

        $this->assertArrayHasKey('bar', $config);
        $this->assertEquals('barVal', $config['bar']);
    }

    public function testCreateNonSandboxGateway()
    {
        $factory = new TpayGatewayFactory();
        $gateway = $factory->create([
            'sandbox' => false,
            'merchant_id' => '1234',
            'secret' => 's3cr3t',
            'api_key' => 'asdfgh',
            'api_password' => 'ijkl',
        ]);

        $this->assertInstanceOf('Payum\Core\Gateway', $gateway);
    }

    public function testCreateGatewayConfig()
    {
        $factory = new TpayGatewayFactory();

        $config = $factory->createConfig();

        $this->assertIsArray($config);
        $this->assertNotEmpty($config);
    }
}
