<?php

namespace App\Providers;

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
         'App\Models\CollectionPoint' => 'App\Policies\CollectionPointPolicy',
         'App\Models\Charity' => 'App\Policies\CharityPolicy',
         'App\Models\Tag' => 'App\Policies\TagPolicy',
         'App\Models\User' => 'App\Policies\UserPolicy',
         'App\Models\Order' => 'App\Policies\OrderPolicy',
         'App\Models\Meal' => 'App\Policies\MealPolicy',
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
    }
}
