<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use App\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
            return $user->esAdministrador();
        });

        //Passport::routes();
        //Passport::personalAccessTokensExpireIn(now()->addMonths(3));
        //Passport::refreshTokensExpireIn(now()->addDays(7));
        //Passport::tokensExpireIn(now()->addMonths(3));
        //Passport::refreshTokensExpireIn(now()->addDays(7));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
            'manage-account' => 'Obtener la información de la cuenta, nombre, email, estado (sin contraseña), modificar datos como email, nombre y contraseña. No puede eliminar la cuenta',
        ]);
    }
}
