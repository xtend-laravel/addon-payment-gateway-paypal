<?php

namespace XtendLunar\Addons\PaymentGatewayPaypal;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Lunar\Facades\Payments;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Xtend\Extensions\Lunar\Core\Concerns\XtendLunarCartPipeline;
use XtendLunar\Addons\PaymentGatewayPaypal\Base\PaypalConnectInterface;
use XtendLunar\Addons\PaymentGatewayPaypal\Base\PaypalPayment;
use XtendLunar\Addons\PaymentGatewayPaypal\Pipelines\PaymentIntent;

class PaymentGatewayPaypalProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;
    use XtendLunarCartPipeline;

    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'xtend-lunar::payment-gateway-paypal');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xtend-lunar::payment-gateway-paypal');
        $this->loadRestifyFrom(__DIR__.'/Restify', __NAMESPACE__.'\\Restify\\');

        //$this->registerWithCartPipeline([PaymentIntent::class]);
        $this->mergeConfigFrom(__DIR__.'/../config/paypal.php', 'paypal');
    }

    public function boot()
    {
        Blade::componentNamespace('XtendLunar\\Addons\\PaymentGatewayPaypal\\Components', 'xtend-lunar::payment-gateway-paypal');

        $this->app->singleton(PaypalConnectInterface::class, function ($app) {
            return new PayPalClient;
        });

        Payments::extend('paypal', function ($app) {
            return $app->make(PaypalPayment::class);
        });
    }
}
