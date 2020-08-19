<?php

namespace App\Providers;

use Laravel\Passport\Passport; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
/*
        $this->registerPolicies();
        //Passport::routes();
        //Passport::tokensExpireIn(Carbon::now()->addDays(1));
        //Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));

        Passport::routes(function ($router) {
            $router->forAccessTokens();
            $router->forPersonalAccessTokens();
            $router->forTransientTokens();
        });
        Passport::tokensExpireIn(Carbon::now()->addMinutes(10));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));
*/
        $this->registerPolicies();

        Passport::routes();
        Passport::enableImplicitGrant();

        Passport::tokensExpireIn(now()->addDays(15));
        //Passport::tokensExpireIn(now()->addMinutes(1));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        //Passport::personalAccessTokensExpireIn(now()->addHour(1));
        //Passport::personalAccessTokensExpireIn(now()->addMinutes(1));
    }
}
