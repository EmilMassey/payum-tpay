<?php
namespace EmilMassey\Payum\Tpay\Action\Api;

use EmilMassey\Payum\Tpay\Api;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;

abstract class BaseApiAwareAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    /**
     * @var Api
     */
    protected $api;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }
}
