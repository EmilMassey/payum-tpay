<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\NotifyAction;
use Payum\Core\GatewayInterface;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;
use PHPUnit\Framework\TestCase;

class NotifyActionTest extends TestCase
{
    public function testThrowHttpResponseWithContentTRUE()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $action = new NotifyAction();
        $action->setGateway($gateway);
        $request = new Notify([]);

        try {
            $action->execute($request);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(HttpResponse::class, $exception);
            $this->assertSame('TRUE', $exception->getContent());
        }
    }

    public function testExecutesSync()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->atLeastOnce())
            ->method('execute')
            ->with($this->isInstanceOf(Sync::class));
        $action = new NotifyAction();
        $action->setGateway($gateway);
        $request = new Notify([]);

        try {
            $action->execute($request);
        } catch (HttpResponse $e) {
            // always throws
        }
    }
}
