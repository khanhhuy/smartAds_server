<?php namespace App\Providers;

use App\Services\ConnectorService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ConnectorServiceProvider extends ServiceProvider {


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
		App::bind('connector',function(){
            return new ConnectorService();
        });
	}

}
