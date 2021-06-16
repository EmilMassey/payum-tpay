# Tpay
The Payum extension. It provides [Tpay](https://tpay.com) payment integration.

## Installation
Just run `composer require emilmassey/payum-tpay "^1.1"`

## config.php
```php
<?php
//config.php

use Payum\Core\PayumBuilder;
use Payum\Core\Payum;

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()

    ->addGateway('gatewayName', [
        'factory' => 'tpay',
        'merchant_id'  => 'change it',
        'secret' => 'change it',
        'api_key'  => 'change it',
        'api_password' => 'change it',
        'sandbox'   => true,
    ])

    ->getPayum()
;
```
## prepare.php

Here you have to modify the `gatewayName` value. Set it to `tpay`. The rest remain the same as described in basic [get it started](https://github.com/Payum/Payum/blob/master/docs/get-it-started.md) documentation.

## Resources

* [Site](https://payum.forma-pro.com/)
* [Documentation](https://github.com/Payum/Payum/blob/master/docs/index.md#general)
* [Questions](http://stackoverflow.com/questions/tagged/payum)
* [Issue Tracker](https://github.com/Payum/Payum/issues)
* [Twitter](https://twitter.com/payumphp)
* [Tpay Documentation](https://docs.tpay.com)

## License

Extension is released under the [MIT License](LICENSE).
