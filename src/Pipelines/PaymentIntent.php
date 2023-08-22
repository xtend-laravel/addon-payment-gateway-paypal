<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Pipelines;

use Closure;
use Lunar\Models\Cart;
use XtendLunar\Addons\PaymentGatewayPaypal\Concerns\WithPaypalClient;

class PaymentIntent
{
    use WithPaypalClient;

    /**
     * Called after cart totals have been calculated.
     *
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        // Ignores current cart getter request
        if (request()->route()->parameter('getter') === 'current-cart') {
            return $next($cart);
        }

        $this->initPaypal();

        $cart->update([
            'meta' => collect($cart->meta ?? [])->merge([

            ]),
        ]);

        return $next($cart);
    }
}
