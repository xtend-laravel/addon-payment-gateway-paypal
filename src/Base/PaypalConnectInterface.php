<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Base;

interface PaypalConnectInterface
{
    public function createCustomer(string $email): string;

    public function createProduct(string $name): string;

    public function createPrice(string $productId, int $amount): string;

    public function createCheckoutSession(array $data): string;
}
