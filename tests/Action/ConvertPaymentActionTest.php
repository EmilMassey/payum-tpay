<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\ConvertPaymentAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayInterface;
use Payum\Core\ISO4217\Currency;
use Payum\Core\Model\Payment;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetCurrency;
use PHPUnit\Framework\TestCase;

class ConvertPaymentActionTest extends TestCase
{
    public function testThrowIfSourceNotPaymentInterface()
    {
        $this->expectException(RequestNotSupportedException::class);

        $action = new ConvertPaymentAction();
        $action->execute(new Convert([], 'array'));
    }

    public function testDivisor()
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects($this->atLeastOnce())
            ->method('execute')
            ->with($this->equalTo(new GetCurrency('PLN')))
            ->willReturnCallback(function (GetCurrency $request) {
                $request->name = 'Polish Zloty';
                $request->alpha3 = 'PLN';
                $request->numeric = 985;
                $request->exp = 2;
                $request->country = 'PL';
            });

        $payment = $this->createPayment();
        $payment->setCurrencyCode('PLN');
        $payment->setTotalAmount(100);

        $action = new ConvertPaymentAction();
        $action->setGateway($gateway);
        $request = new Convert($payment, 'array');

        $action->execute($request);
        $model = $request->getResult();

        $this->assertSame(1, $model['amount']);
    }

    public function testCopiesDetails()
    {
        $gateway = $this->createMock(GatewayInterface::class);

        $payment = $this->createPayment();
        $payment->setCurrencyCode('PLN');
        $payment->setDetails(['lorem' => 'ipsum']);

        $action = new ConvertPaymentAction();
        $action->setGateway($gateway);
        $request = new Convert($payment, 'array');

        $action->execute($request);
        $model = $request->getResult();

        $this->assertArrayHasKey('lorem', $model);
    }

    public function testPopulatesFields()
    {
        $gateway = $this->createMock(GatewayInterface::class);

        $payment = $this->createPayment();
        $payment->setCurrencyCode('PLN');
        $payment->setClientEmail('emil@rainbowbrains.pl');
        $payment->setDescription('Lorem Ipsum');

        $action = new ConvertPaymentAction();
        $action->setGateway($gateway);
        $request = new Convert($payment, 'array');

        $action->execute($request);
        $model = $request->getResult();

        $this->assertArrayHasKey('email', $model);
        $this->assertArrayHasKey('description', $model);
        $this->assertSame('emil@rainbowbrains.pl', $model['email']);
        $this->assertSame('Lorem Ipsum', $model['description']);
    }

    private function createPayment(): Payment
    {
        return new Payment();
    }
}
