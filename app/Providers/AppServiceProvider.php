<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \URL::forceScheme('http');

	// if(config('app.env') === 'local' || config('app.env') === 'localsaya' || config('app.env') === 'production') {
    //         \URL::forceScheme('https');
    //         $this->app['request']->server->set('HTTPS',true);
    //     } else {
    //         $this->app['request']->server->set('HTTP',true);
    //     }
    
        Paginator::useBootstrap();
    }
}
