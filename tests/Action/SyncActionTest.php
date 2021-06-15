<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\SyncAction;
use EmilMassey\Payum\Tpay\Request\Api\GetTransactionData;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Sync;
use PHPUnit\Framework\TestCase;

class SyncActionTest extends TestCase
{
    public function testDoesNothingIfTitleNotSet()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->never())
            ->method('execute');
        $action = new SyncAction();
        $action->setGateway($gateway);

        $action->execute(new Sync([]));
    }

    public function testGetTransactionData()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(GetTransactionData::class));
        $action = new SyncAction();
        $action->setGateway($gateway);

        $action->execute(new Sync(['title' => 'lorem_ipsum']));
    }
}
