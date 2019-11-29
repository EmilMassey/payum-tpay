<?php

namespace EmilMassey\Payum\Tpay\Action\Api;

use EmilMassey\Payum\Tpay\Request\Api\CreateTransaction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;

class CreateTransactionAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param $request CreateTransaction
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($model['title']) {
            throw new LogicException(sprintf('The transaction has already been created for this payment. title: %s', $model['title']));
        }

        $model->validateNotEmpty(['amount', 'description', 'return_url', 'result_url', 'name', 'group']);

        $model->replace($this->api->createTransaction((array)$model));

        if ($model['url']) {
            throw new HttpRedirect($model['url']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateTransaction &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
