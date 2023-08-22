<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

use Illuminate\Support\Facades\App;
use XtendLunar\Addons\PaymentGatewayPaypal\Base\PaypalConnectInterface;

trait WithPaypalClient
{
    protected static mixed $paypal;

    protected static function initPaypal(): void
    {
        static::$paypal = App::make(PaypalConnectInterface::class);
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