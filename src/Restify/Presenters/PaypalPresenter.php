<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal\Restify\Presenters;

use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class PaypalPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        return $this->data;
    }
}
