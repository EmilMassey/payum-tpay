<?php
namespace EmilMassey\Payum\Tpay\Action;

use EmilMassey\Payum\Tpay\Request\Api\CreateTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Sync;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;

class CaptureAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (null === $model['title']) {
            if (empty($model['return_url']) && $request->getToken()) {
                $model['return_url'] = $request->getToken()->getTargetUrl();
            }

            if (empty($model['return_error_url']) && $request->getToken()) {
                $model['return_error_url'] = $request->getToken()->getTargetUrl();
            }

            if (empty($model['result_url']) && $request->getToken() && $this->tokenFactory) {
                $notifyToken = $this->tokenFactory->createNotifyToken(
                    $request->getToken()->getGatewayName(),
                    $request->getToken()->getDetails()
                );
                $model['result_url'] = $notifyToken->getTargetUrl();
            }

            $this->gateway->execute(new CreateTransaction($model));
        }

        $this->gateway->execute(new Sync($model));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
