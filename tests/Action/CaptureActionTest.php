<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\CaptureAction;
use EmilMassey\Payum\Tpay\Request\Api\CreateTransaction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayInterface;
use Payum\Core\Model\ArrayObject;
use Payum\Core\Model\Token;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Generic;
use Payum\Core\Request\Sync;
use Payum\Core\Security\GenericTokenFactoryInterface;
use PHPUnit\Framework\TestCase;

class CaptureActionTest extends TestCase
{
    public function testExecuteThrowIfNotSupportedRequest()
    {
        $this->expectException(RequestNotSupportedException::class);

        $action = new CaptureAction();
        $request = new class([]) extends Generic {};

        $action->execute($request);
    }

    public function testSetReturnUrls()
    {
        $action = new CaptureAction();
        $action->setGateway($this->createMock(GatewayInterface::class));
        $token = new Token();
        $token->setTargetUrl('https://example.com');
        $token->setDetails(new ArrayObject());

        $request = new Capture($token);
        $request->setModel([]);

        $action->execute($request);

        $model = $request->getModel();

        $this->assertArrayHasKey('return_url', $model);
        $this->assertArrayHasKey('return_error_url', $model);
        $this->assertSame('https://example.com', $model['return_url']);
        $this->assertSame('https://example.com', $model['return_error_url']);
    }

    public function testSetResultUrl()
    {
        $token = new Token();
        $token->setTargetUrl('https://example.com');
        $token->setDetails(new ArrayObject());

        $tokenFactory = $this->createMock(GenericTokenFactoryInterface::class);
        $tokenFactory
            ->expects($this->once())
            ->method('createNotifyToken')
            ->willReturn($token);

        $action = new CaptureAction();
        $action->setGateway($this->createMock(GatewayInterface::class));
        $action->setGenericTokenFactory($tokenFactory);

        $request = new Capture($token);
        $request->setModel([]);

        $action->execute($request);

        $model = $request->getModel();

        $this->assertArrayHasKey('return_url', $model);
        $this->assertArrayHasKey('return_error_url', $model);
        $this->assertSame('https://example.com', $model['return_url']);
        $this->assertSame('https://example.com', $model['return_error_url']);
    }

    public function testExecutesCreateTransaction()
    {
        // TODO refactor test to not use at() as it is deprecated
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->at(0))
            ->method('execute')
            ->with($this->isInstanceOf(CreateTransaction::class));

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $token = new Token();
        $token->setTargetUrl('https://example.com');
        $token->setDetails(new ArrayObject());

        $request = new Capture($token);
        $request->setModel([]);

        $action->execute($request);
    }

    public function testExecutesSync()
    {
        // TODO refactor test to not use at() as it is deprecated
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->at(1))
            ->method('execute')
            ->with($this->isInstanceOf(Sync::class));

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $token = new Token();
        $token->setTargetUrl('https://example.com');
        $token->setDetails(new ArrayObject());

        $request = new Capture($token);
        $request->setModel([]);

        $action->execute($request);
    }

    public function testNotCreateTransactionIfTitleSet()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->atLeastOnce())
            ->method('execute')
            ->with($this->logicalNot($this->isInstanceOf(CreateTransaction::class)));

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $request = new Capture(['title' => '123432']);

        $action->execute($request);
    }

    public function testDoesNotSupportOtherRequest()
    {
        $action = new CaptureAction();
        $request = new class([]) extends Generic {
        };

        $this->assertFalse($action->supports($request));
    }

    public function testDoesSupportCaptureWithArrayModel()
    {
        $action = new CaptureAction();
        $request = new Capture([]);

        $this->assertTrue($action->supports($request));
    }

    public function testDoesNotSupportNonArrayAccessModel()
    {
        $action = new CaptureAction();
        $request = new Capture(new \stdClass());

        $this->assertFalse($action->supports($request));
    }

    public function testSupportCaptureRequestWithArrayObjectModel()
    {
        $action = new CaptureAction();
        $request = new Capture(new ArrayObject());

        $this->assertTrue($action->supports($request));
    }
}
