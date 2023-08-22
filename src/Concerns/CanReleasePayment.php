<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Concerns;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;

trait CanReleasePayment
{
    use WithPaypalClient;

    /**
     * Return a successfully released payment.
     *
     * @return void
     */
    private function releaseSuccess(): PaymentAuthorize
    {
        return new PaymentAuthorize(success: true);
    }
}
