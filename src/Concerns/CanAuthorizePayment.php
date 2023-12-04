<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;

trait CanAuthorizePayment
{
    use CanReleasePayment;

    public function authorize(): PaymentAuthorize
    {
        if ($this->cart?->order?->placed_at) {
            return new PaymentAuthorize(
                success: false,
                message: 'This order has already been placed',
            );
        }

        $paypalOrderId = $this->cart->meta?->paypal_order_id ?? null;
        $this->paymentIntent = static::$paypal->showOrderDetails($paypalOrderId);

        if ($this->paymentIntent['status'] === 'APPROVED') {
            $this->paymentIntent = static::$paypal->capturePaymentOrder($paypalOrderId);

            if ($this->paymentIntent['error'] ?? false) {
                return new PaymentAuthorize(
                    success: false,
                    message: collect($this->paymentIntent['error']['details'])->map(function ($detail) {
                        return $detail['description'].'=>'.$detail['issue'];
                    })->implode(' '),
                    orderId: $this->order->id,
                );
            }
            // @todo Handle errors

            $this->cart->update([
                'meta' => collect($this->cart->meta ?? [])->merge([
                    'paypal_payment_intent' => $this->paymentIntent['id'],
                ]),
            ]);
        }

        if (! $this->isPaymentIntentApproved()) {
            return new PaymentAuthorize(
                success: false,
                message: 'Payment not approved',
            );
        }

        return $this->releaseSuccess($this->paymentIntent);
    }

    protected function isPaymentIntentApproved(): bool
    {
        return in_array($this->paymentIntent['status'], [
            'COMPLETED',
            'APPROVED',
        ]);
    }
}
