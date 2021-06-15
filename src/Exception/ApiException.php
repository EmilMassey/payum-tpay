<?php

namespace EmilMassey\Payum\Tpay\Exception;

use tpayLibs\src\_class_tpay\Utilities\TException;

class ApiException extends \RuntimeException
{
    public static function create(TException $exception): self
    {
        return new self(\sprintf('TPay Api Exception: %s', $exception->getMessage()), $exception->getCode(), $exception);
    }

    private function __construct($message = "TPay Api Exception", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
