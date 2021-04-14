<?php

namespace App\Providers;

use Dashboard\MealsDisplay\MealsDisplay;
use Dashboard\Orders\Orders;
use Dashboard\QuickLinks\QuickLinks;
use Dashboard\SettingDisplay\SettingDisplay;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                'afmire877@gmail.com'
            ]) || $user->type === "charity";
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        if(auth()->user()->type === "admin" ) return [];
        return [
            (new SettingDisplay())->withSettingData(),
            (new Orders())->withOrders(),
            (new MealsDisplay())->withTodaysMeals(),
            (new MealsDisplay())->withMealsCapacity(),
            (new QuickLinks())->addLinks()
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        if(auth()->user()->type === "admin" ) return [];
        return [
            (new \vmitchell85\NovaLinks\Links())
                ->add('My Charity', url("/nova/resources/charities/". auth()->user()->charity()->id))
                ->add('My Collection Point', url("/nova/resources/collection-points/". auth()->user()->charity()->collectionPoints->first()->id)),

        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
