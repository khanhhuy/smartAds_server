<?php namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Services\ConnectorService;

class ConnectorServiceProvider extends ServiceProvider {


    protected $defer =true;

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

    public function provides()
    {
        return ['connector'];
    }
}
