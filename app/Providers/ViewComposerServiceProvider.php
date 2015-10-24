<?php namespace App\Providers;

use App\Area;
use App\Store;
use DB;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Request;
use Route;

class ViewComposerServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     */
	public function boot()
	{
		View::composer(['ads.partials.promotion-form', 'ads.partials.targeted-form'],function($view){
            $stores=Store::lists('name','id');
            $areas=Area::lists('name','id');
            $targets=['Stores'=>$stores,'Areas'=>$areas];
            $view->with(compact('targets'));
		});
		View::composer('majors.partials.create',function ($view){
            $allStores = Store::leftJoin('beacon_majors', 'stores.id', '=', 'beacon_majors.store_id')->whereNull('major')->get();
            $stores = [];
            foreach ($allStores as $s) {
                $stores[$s->id] = $s->name . " <br/><small>(" . $s->display_area . ')</small>';
            }
            $r=DB::select("SHOW TABLE STATUS LIKE 'beacon_majors'");
            $nextID=$r[0]->Auto_increment;
            $view->with(compact('stores','nextID'));
        });
        View::creator('majors.partials.edit',function ($view){
            $allStores = Store::leftJoin('beacon_majors', 'stores.id', '=', 'beacon_majors.store_id')->whereNull('major')->get();
            $major=Route::input('majors');
            $allStores->prepend(Store::find($major->store_id));

            $stores = [];
            foreach ($allStores as $s) {
                $stores[$s->id] = $s->name . " <br/>(" . $s->display_area . ')';
            }
            $nextID=null;
            $view->with(compact('stores','nextID'));
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
