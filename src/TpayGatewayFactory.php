<?php
namespace EmilMassey\Payum\Tpay;

use EmilMassey\Payum\Tpay\Action\Api\CreateTransactionAction;
use EmilMassey\Payum\Tpay\Action\Api\GetTransactionDataAction;
use EmilMassey\Payum\Tpay\Action\CaptureAction;
use EmilMassey\Payum\Tpay\Action\ConvertPaymentAction;
use EmilMassey\Payum\Tpay\Action\NotifyAction;
use EmilMassey\Payum\Tpay\Action\RefundAction;
use EmilMassey\Payum\Tpay\Action\StatusAction;
use EmilMassey\Payum\Tpay\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class TpayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'tpay',
            'payum.factory_title' => 'Tpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.api.create_transaction' => new CreateTransactionAction(),
            'payum.action.api.get_transaction_data' => new GetTransactionDataAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'language' => 'en',
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['merchant_id', 'secret', 'api_key', 'api_password'];

            $config['payum.api'] = function (ArrayObject $config): Api {
                if (!isset($config['sandbox']) || true !== $config['sandbox']) {
                    $config->validateNotEmpty($config['payum.required_options']);
                }

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
