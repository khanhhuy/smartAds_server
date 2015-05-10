<?php namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Services\ContextAdsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ContextAdsServiceProvider extends ServiceProvider {

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
        App::bind('App\Repositories\CategoryInterface',function($app){
           return new CategoryRepository();
        });
		App::bind('contextAdsService',function($app){
            return new ContextAdsService($app->make('App\Repositories\CategoryInterface'));
        });
	}

}
