<?php

namespace EmilMassey\Payum\Tpay\Api;

use tpayLibs\src\_class_tpay\TransactionApi as BaseTransactionApi;
use tpayLibs\src\_class_tpay\Utilities\TException;

class TransactionApi extends BaseTransactionApi
{
    public function __construct(array $config)
    {
        if (!isset($config['sandbox']) || true !== $config['sandbox']) {
            $this->merchantId = $config['merchant_id'];
            $this->merchantSecret = $config['secret'];
            $this->trApiKey = $config['api_key'];
            $this->trApiPass = $config['api_password'];
        } else {
            $this->merchantId = 1010;
            $this->merchantSecret = 'demo';
            $this->trApiKey = '75f86137a6635df826e3efe2e66f7c9a946fdde1';
            $this->trApiPass = 'p@$$w0rd#@!';
        }

        parent::__construct();
    }

    /**
     * @param string|int $transactionTitle
     *
     * @return array
     *
     * @throws TException
     */
    public function getTransaction($transactionTitle): array
    {
        $this->setTransactionID($transactionTitle);

        return $this->get();
    }

    /**
     * @param array $model
     *
     * @return array
     *
     * @throws TException
     */
    public function createTransaction(array $model): array
    {
        return $this->create($model);
    }
}
