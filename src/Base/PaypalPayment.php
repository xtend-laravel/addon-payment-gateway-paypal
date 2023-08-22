<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Base;

use Lunar\PaymentTypes\AbstractPayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanAuthorizePayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanCapturePayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\CanRefundPayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\WithPaypalClient;
use XtendLunar\Features\PaymentGateways\Contracts\OnlinePaymentGateway;

class PaypalPayment extends AbstractPayment implements OnlinePaymentGateway
{
    use WithPaypalClient;
    use CanAuthorizePayment;
    use CanCapturePayment;
    use CanRefundPayment;

    public function init(): self
    {
        $this->initPaypal();
        return $this;
    }

    public function handle()
    {
        dd('handle');
        // Check if we have a valid order in the cart
        if (! $this->cart->hasOrder()) {
            return;
        }

        // Get the order
        $this->order = $this->cart->order;
    }
}
