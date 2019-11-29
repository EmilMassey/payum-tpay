<?php
namespace EmilMassey\Payum\Tpay;

use EmilMassey\Payum\Tpay\Api\RefundApi;
use EmilMassey\Payum\Tpay\Api\TransactionApi;
use EmilMassey\Payum\Tpay\Exception\ApiException;
use Http\Message\MessageFactory;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\HttpClientInterface;
use tpayLibs\src\_class_tpay\Utilities\TException;
use tpayLibs\src\_class_tpay\Utilities\Util;

class Api
{
    const STATUS_CORRECT = 'correct';
    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    const STATUS_ERROR = 'error';
    const STATUS_CHARGEBACK = 'chargeback';

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     * @param HttpClientInterface $client
     * @param MessageFactory $messageFactory
     *
     * @throws InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;

        Util::$loggingEnabled = false;

        if (isset($this->options['language'])) {
            Util::$lang = $this->options['language'];
        }
    }

    public function createTransaction(array $model): array
    {
        $api = new TransactionApi($this->options);

        try {
            return $api->createTransaction($model);
        } catch (TException $exception) {
            throw ApiException::create($exception);
        }
    }

    public function getTransactionData(string $transactionTitle): array
    {
        $api = new TransactionApi($this->options);

        try {
            return $api->getTransaction($transactionTitle);
        } catch (TException $exception) {
            throw ApiException::create($exception);
        }
    }

    public function refundTransaction(string $transactionTitle): void
    {
        $api = new RefundApi($this->options);

        try {
            $api->refundTransaction($transactionTitle);
        } catch (TException $exception) {
            throw ApiException::create($exception);
        }
    }
}
