<?php


namespace App\Providers;


use App\Services\TwilioService;
use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TwilioService::class, function ($app) {
            return new TwilioService(
                env('TWILIO_SID'),
                env('TWILIO_AUTH_TOKEN'),
                env('TWILIO_SMS_NUMBER')
            );
        });
    }
}
