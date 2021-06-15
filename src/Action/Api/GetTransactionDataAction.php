<?php

namespace EmilMassey\Payum\Tpay\Action\Api;

use EmilMassey\Payum\Tpay\Request\Api\GetTransactionData;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;

class GetTransactionDataAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param $request GetTransactionData
     *
     * @return void
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $model['title']) {
            throw new LogicException('The parameter "title" must be set. Have you run CreateTransactionAction?');
        }

        $model->replace($this->api->getTransactionData($model['title']));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetTransactionData &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
