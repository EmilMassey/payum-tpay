<?php

namespace EmilMassey\Payum\Tpay\Api;

use tpayLibs\src\_class_tpay\Refunds\BasicRefunds;
use tpayLibs\src\_class_tpay\Utilities\TException;

class RefundApi extends BasicRefunds
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
     * @return void
     *
     * @throws TException
     */
    public function refundTransaction($transactionTitle): void
    {
        $this->setTransactionID($transactionTitle);

        $this->refund();
    }
}
