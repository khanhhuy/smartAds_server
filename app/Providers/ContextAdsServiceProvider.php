<?php namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\StoreRepository;
use App\Services\ContextAdsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ContextAdsServiceProvider extends ServiceProvider
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
        App::bind('App\Repositories\StoreRepositoryInterface', 'App\Repositories\StoreRepository');
        App::bind('App\Repositories\CategoryRepositoryInterface', 'App\Repositories\CategoryRepository');
        App::bind('contextAdsService', function ($app) {
            return new ContextAdsService($app->make('App\Repositories\CategoryRepositoryInterface'),
                $app->make('App\Repositories\StoreRepositoryInterface'));
        });
    }
}
