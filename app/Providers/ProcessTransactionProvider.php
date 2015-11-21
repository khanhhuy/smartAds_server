<?php namespace App\Providers;

use App\Services\ProcessTransactionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ProcessTransactionProvider extends ServiceProvider {

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
		App::bind('processTransaction', function(){
            return new ProcessTransactionService();
        });
	}

    public function provides()
    {
        return ['processTransaction'];
    }
}
