<?php

namespace EmilMassey\Payum\Tpay\Tests;

use EmilMassey\Payum\Tpay\Api;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Payum\Core\HttpClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use tpayLibs\src\_class_tpay\Utilities\Util;

class ApiTest extends TestCase
{
    public function testConstructorSetsTpayLanguageIfOptionPassed()
    {
        $client = $this->createHttpClientMock();
        $factory = $this->createHttpMessageFactory();

        new Api([
            'language' => 'pl',
        ], $client, $factory);

        $this->assertSame('pl', Util::$lang);
    }

    /**
     * @return MockObject|HttpClientInterface
     */
    protected function createHttpClientMock()
    {
        return $this->createMock('Payum\Core\HttpClientInterface');
    }

    /**
     * @return \Http\Message\MessageFactory
     */
    protected function createHttpMessageFactory()
    {
        return new GuzzleMessageFactory();
    }
}
