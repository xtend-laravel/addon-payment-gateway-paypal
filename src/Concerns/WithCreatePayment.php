<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

trait WithCreatePayment
{
    public function create(): mixed
    {
        static::$paypal->getAccessToken();

        // $orderId = $this->cart->meta->paypal_order_id ?? null;
        // $paypalOrder = !$orderId
        //    ? $this->createOrder()
        //    : $this->updateOrder();
        // @todo Client token does not generate on update so creating a new order each tune seems to be the best suggested solution.

        $paypalOrder = $this->createOrder();

        if ($paypalOrder['status'] !== 'CREATED') {
           throw new \Exception($paypalOrder['status']);
        }

        $this->cart->update([
            'meta' => collect($this->cart->meta ?? [])->merge([
                'paypal_client_id' => config('paypal.sandbox.client_id'),
                'paypal_order_id' => $paypalOrder['id'],
                'paypal_client_token' => static::$paypal->getClientToken()['client_token'],
            ]),
        ]);

        return [
            'paypal' => [
                'clientId' => config('paypal.sandbox.client_id'),
                'orderId' => $paypalOrder['id'],
            ],
        ];
    }

    protected function createOrder(): array
    {
        $shipping = $this->cart->shippingAddress;
        $shippingRequest = $this->cart->shippingAddress ? [
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
                        'currency_code' => $this->cart->currency->code,
                        'value' => $this->cart->total->value / 100,
                    ],
                    'custom_id' => $this->cart->id,
                    ...$shippingRequest,
                ],
            ],
        ]);
    }

    protected function updateOrder(): array
    {
        $updateRequestBody = [
            [
                'op' => 'replace',
                'path' => '/purchase_units/@reference_id==\'default\'/amount',
                'value' => [
                    'currency_code' => $this->cart->currency->code,
                    'value' => $this->cart->total->value,
                ],
            ],
        ];

        $response = static::$paypal->updateOrder($this->cart->meta->paypal_order_id, $updateRequestBody);

        if ($response['error'] ?? null) {
            throw new \Exception($response['error']);
        }

        return static::$paypal->showOrderDetails($this->cart->meta->paypal_order_id);
    }
}
