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

        if ($this->cart) {
            $this->cart->update([
                'meta' => collect($cart->meta ?? [])->merge([

                ]),
            ]);
        }

        if (! in_array($this->paymentIntent->status, [
            'processing',
            'requires_capture',
            'succeeded',
        ])) {
            return new PaymentAuthorize(
                success: false,
                message: $this->paymentIntent->last_payment_error ?? 'Payment intent is not in a valid state',
            );
        }

        return $this->releaseSuccess();
    }
}
