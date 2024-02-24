<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Restify;

use XtendLunar\Addons\PaymentGatewayStripe\Restify\Presenters\StripePresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class PaypalRepository extends Repository
{
    public static string $presenter = StripePresenter::class;
}
