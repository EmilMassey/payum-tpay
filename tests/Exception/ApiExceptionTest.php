<?php

namespace EmilMassey\Payum\Tpay\Tests\Exception;

use EmilMassey\Payum\Tpay\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use tpayLibs\src\_class_tpay\Utilities\TException;

class ApiExceptionTest extends TestCase
{
    public function testCreateFromTPayExceptionSetsPrevious()
    {
        $tpayException = new TException('Lorem ipsum');
        $exception = ApiException::create($tpayException);

        $this->assertSame($tpayException, $exception->getPrevious());
    }

    public function testCreateFromTPayExceptionCopiesMessage()
    {
        // TException is weirdly constructed, so to simplify our test (we don't test TException), let's override it
        $tpayException = new class('Lorem ipsum') extends TException {
            public function __construct($message, $code = 0)
            {
                return $this->message = $message;
            }
        };

        $exception = ApiException::create($tpayException);

        $this->assertSame('TPay Api Exception: Lorem ipsum', $exception->getMessage());
    }
}
