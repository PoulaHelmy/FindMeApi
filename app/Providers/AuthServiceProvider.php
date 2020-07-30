<?php

namespace App\Providers;
use Carbon\Carbon;
use Laravel\Passport\Passport;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        // Mandatory to define Scope
//        Passport::tokensCan([
//            'admin' => 'Add/Edit/Delete Users',
//            'moderator' => 'Add/Edit Users',
//            'basic' => 'List Users'
//        ]);
//
//        Passport::setDefaultScope([
//            'basic'
//        ]);
    }
}
