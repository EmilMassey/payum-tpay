<?php
namespace EmilMassey\Payum\Tpay\Action;

use EmilMassey\Payum\Tpay\Api;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     *
     * @return void
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (empty($model['title']) || !isset($model['status'])) {
            $request->markNew();

            return;
        }

        switch ($model['status']) {
            case Api::STATUS_PAID:
                $request->markCaptured();
                break;
            case Api::STATUS_CORRECT:
                if (isset($model['amount_paid']) && $model['amount_paid'] >= $model['amount']) {
                    $request->markCaptured();
                } else {
                    $request->markPending();
                }
                break;
            case Api::STATUS_PENDING:
                $request->markPending();
                break;
            case Api::STATUS_CHARGEBACK:
                $request->markRefunded();
                break;
            case Api::STATUS_ERROR:
                $request->markFailed();
                break;
            default:
                $request->markUnknown();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
