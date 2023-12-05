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
        $orderId = $cart->meta->paypal_order_id ?? null;
        $clientToken = $cart->meta->paypal_client_token ?? null;

        $paypalOrder = !$orderId
           ? $this->createOrder($cart)
           : $this->updateOrder($cart);

        $cart->update([
            'meta' => collect($cart->meta ?? [])->merge([
                'paypal_client_id' => config('paypal.mode') === 'sandbox'
                    ? config('paypal.sandbox.client_id')
                    : config('paypal.live.client_id'),
                'paypal_order_id' => $paypalOrder['id'],
                'paypal_client_token' => $clientToken ?? static::$paypal->getClientToken()['client_token'],
            ]),
        ]);

        return $next($cart);
    }

    protected function createOrder(Cart $cart): array
    {
        $shipping = $cart->shippingAddress;
        $shippingRequest = $cart->shippingAddress ? [
            'shipping' => [
                'name' => [
                    'full_name' => "{$shipping->first_name} {$shipping->last_name}",
                ],
                'address' => [
                    'address_line_1' => $shipping->line_one,
                    'address_line_2' => $shipping->line_two,
                    'admin_area_2' => $shipping->city,
                    'admin_area_1' => $shipping->state,
                    'postal_code' => $shipping->postcode,
                    'country_code' => $shipping->country?->iso2 ?? 'US',
                ],
            ],
        ] : [];

        return static::$paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $cart->currency->code,
                        'value' => $cart->total->value / 100,
                    ],
                    'custom_id' => $cart->id,
                    ...$shippingRequest,
                ],
            ],
        ]);
    }

    /**
     * @throws \Throwable
     */
    protected function updateOrder(Cart $cart): array
    {
        $updateRequestBody = [
            [
                'op' => 'replace',
                'path' => '/purchase_units/@reference_id==\'default\'/amount',
                'value' => [
                    'currency_code' => $cart->currency->code,
                    'value' => $cart->total->value / 100,
                ],
            ],
        ];

        $response = static::$paypal->updateOrder($cart->meta->paypal_order_id, $updateRequestBody);

        if ($response['error'] ?? null) {
            throw new \Exception($response['error']);
        }

        return static::$paypal->showOrderDetails($cart->meta->paypal_order_id);
    }
}
