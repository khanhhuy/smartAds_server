<?php namespace App\Providers;

use App\Area;
use App\Store;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		View::composer('ads.partials.promotion-form',function($view){
            $stores=Store::lists('name','id');
            $areas=Area::lists('name','id');
            $targets=['Stores'=>$stores,'Areas'=>$areas];
            $view->with(compact('targets'));
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
