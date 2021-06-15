<?php

namespace EmilMassey\Payum\Tpay\Tests\Action;

use EmilMassey\Payum\Tpay\Action\StatusAction;
use EmilMassey\Payum\Tpay\Api;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\BaseGetStatus;
use Payum\Core\Request\Generic;
use Payum\Core\Request\GetBinaryStatus;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\GetStatusInterface;
use PHPUnit\Framework\TestCase;

class StatusActionTest extends TestCase
{
    public function testNewIfEmptyModel()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([]);

        $action->execute($request);

        $this->assertTrue($request->isNew());
    }

    public function testNewIfEmptyStatus()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '324234',
        ]);

        $action->execute($request);

        $this->assertTrue($request->isNew());
    }

    public function testNewIfEmptyTitle()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'status' => 'dummy'
        ]);

        $action->execute($request);

        $this->assertTrue($request->isNew());
    }

    public function testUnknown()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => 'dummy',
        ]);

        $action->execute($request);

        $this->assertTrue($request->isUnknown());
    }

    public function testPaidResolvesToCaptured()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_PAID,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isCaptured());
    }

    public function testCorrectResolvesToPendingIfAmountPaidUnknown()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CORRECT,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isPending());
    }

    public function testCorrectResolvesToPendingIfAmountPaidNotEnough()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CORRECT,
            'amount_paid' => 10,
            'amount' => 20,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isPending());
    }

    public function testCorrectResolvesToPendingIfAmountPaidEqualsAmountDue()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CORRECT,
            'amount_paid' => 20,
            'amount' => 20,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isCaptured());
    }

    public function testCorrectResolvesToPendingIfAmountPaidMoreThanAmountDue()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CORRECT,
            'amount_paid' => 200,
            'amount' => 20,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isCaptured());
    }

    public function testPending()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CORRECT,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isPending());
    }

    public function testChargebackResolvesToRefunded()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_CHARGEBACK,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isRefunded());
    }

    public function testErrorResolvesToFailed()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([
            'title' => '4324234',
            'status' => Api::STATUS_ERROR,
        ]);

        $action->execute($request);

        $this->assertTrue($request->isFailed());
    }

    public function testNotSupportGenericRequest()
    {
        $action = new StatusAction();
        $request = new class([]) extends Generic {};

        $this->assertFalse($action->supports($request));
    }

    public function testNotSupportNonArrayAccessModel()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus('string');

        $this->assertFalse($action->supports($request));
    }

    public function testExecuteThrowIfNotSupportedRequest()
    {
        $this->expectException(RequestNotSupportedException::class);

        $action = new StatusAction();
        $request = new class([]) extends Generic {};

        $action->execute($request);
    }

    public function testSupportGetHumanStatus()
    {
        $action = new StatusAction();
        $request = new GetHumanStatus([]);

        $this->assertTrue($action->supports($request));
    }
}
