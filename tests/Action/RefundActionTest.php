<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\RefundAction;
use EmilMassey\Payum\Tpay\Request\Api\RefundTransaction;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Refund;
use Payum\Core\Request\Sync;
use PHPUnit\Framework\TestCase;

class RefundActionTest extends TestCase
{
    public function testExecutesRefundAndThenSync()
    {
        // TODO refactor not to use at() that is depraceted

        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->at(0))
            ->method('execute')
            ->with($this->isInstanceOf(RefundTransaction::class));
        $gateway
            ->expects($this->at(1))
            ->method('execute')
            ->with($this->isInstanceOf(Sync::class));

        $action = new RefundAction();
        $action->setGateway($gateway);

        $action->execute(new Refund([]));
    }
}
