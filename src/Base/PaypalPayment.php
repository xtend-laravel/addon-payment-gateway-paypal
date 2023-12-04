<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Base;

use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanAuthorizePayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanCapturePayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanRequestPayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanRefundPayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\WithCreatePayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\WithPaypalClient;
use XtendLunar\Features\PaymentGateways\Base\AbstractPaymentGateway;
use XtendLunar\Features\PaymentGateways\Contracts\OnlinePaymentGateway;

class PaypalPayment extends AbstractPaymentGateway implements OnlinePaymentGateway
{
    use WithPaypalClient;
    use WithCreatePayment;
    use CanAuthorizePayment;
    use CanCapturePayment;
    use CanRefundPayment;

    public function init(): self
    {
        $this->initPaypal();
        return $this;
    }
}
