<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

use Illuminate\Support\Facades\App;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use XtendLunar\Addons\PaymentGatewayPaypal\Base\PaypalConnectInterface;

trait WithPaypalClient
{
    protected static PayPalClient $paypal;

    protected static mixed $accessToken;

    protected static function initPaypal(): void
    {
        static::$paypal = App::make(PaypalConnectInterface::class);
        static::$paypal->getAccessToken();
    }

    protected static function withPaypalHeaders(array $headers = [], ?string $idempotencyKey = null): array
    {
        return array_merge($headers, static::idempotencyKeyHeader($idempotencyKey));
    }

    protected static function idempotencyKeyHeader(?string $idempotencyKey): array
    {
        if (!$idempotencyKey) {
            return [];
        }

        return [
            'idempotency_key' => $idempotencyKey,
        ];
    }
}
