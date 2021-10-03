<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ShelterCartProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('shelter', function ($app) {
            $storage = $app['session'];
            $events = $app['events'];
            $instanceName = 'cart_2';
            $session_key = '88uuiioo99888';

            return new \T7team\Shopcart\Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('shopping_cart')
            );
        });
    }
}
