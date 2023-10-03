<?php

namespace App\Providers;

use App\Models\Customers\Customer;
use App\Observers\CustomerObserver;
use App\User;
use App\Mail\UserMailChanged;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use App\Mail\JobFailedMailable;
use App\Models\FailedPurchases\Repositories\Interfaces\FailedPurchaseRepositoryInterface;
use App\Models\FailedPurchases\Repositories\FailedPurchaseRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($request) {
            // Always show admin if local development
            if (env('APP_ENV') == 'local') {
                return true;
            }
        });
        
        Customer::observe(CustomerObserver::class);

        Carbon::setLocale(config('app.locale'));
        Schema::defaultStringLength(191);
        User::updated(function($user) {
            if ($user->isDirty('email')) {
                retry(5, function() use ($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        //Add this custom validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);

        });

        // This was done for the telescope
        if (env('REDIRECT_HTTPS', true)) {
            URL::forceScheme('https');
        }

        Queue::failing(function (JobFailed $event){
            Mail::to(env('EMAIL_FAILED_JOBS', 'soporte@peruapps.com.pe'))->send(new JobFailedMailable($event));
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        // Se agrego parametros que no salian para las instancias
        $this->app->bind(
            \App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface::class,
            \App\Models\MongoMovies\Repositories\MongoMovieRepository::class
        );
        $this->app->bind(FailedPurchaseRepositoryInterface::class, FailedPurchaseRepository::class);
    }
}
