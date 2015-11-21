<?php namespace App\Providers;

use App\Services\ContextAdsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ContextAdsServiceProvider extends ServiceProvider
{
    protected $defer = true;

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
        App::bind('App\Repositories\StoreRepositoryInterface', 'App\Repositories\StoreRepository');
        App::bind('App\Repositories\CustomerRepositoryInterface', 'App\Repositories\CustomerRepository');
        App::bind('contextAdsService', function ($app) {
            return new ContextAdsService($app->make('App\Repositories\CategoryRepositoryInterface'),
                $app->make('App\Repositories\StoreRepositoryInterface'),
                $app->make('App\Repositories\CustomerRepositoryInterface')
                );
        });
    }

    public function provides()
    {
        return ['App\Repositories\StoreRepositoryInterface', 'App\Repositories\CustomerRepositoryInterface', 'contextAdsService'];
    }

}
