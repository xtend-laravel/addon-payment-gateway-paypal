<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

trait WithCreatePayment
{
    public function create(): mixed
    {
        $paypalOrderId = $this->cart->meta->paypal_order_id ?? null;
        $paypalOrder = static::$paypal->showOrderDetails($paypalOrderId);

        return [
            'paypal' => [
                'clientId' => config('paypal.mode') === 'sandbox'
                    ? config('paypal.sandbox.client_id')
                    : config('paypal.live.client_id'),
                'orderId' => $paypalOrder['id'],
            ],
        ];
    }
}
