<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|---------------------- API for Mobile -----------------------|
*/
Route::group(['prefix' => 'api/v1'], function () {

    Route::group(['middleware' => 'apiAuth'], function () {
        Route::get('customers/{customers}/context-ads/{majors}/{minors}', 'ContextAdsController@index');
        Route::post('customers/{customers}/update-request', 'AccountController@update');
        Route::post('customers/{customers}/feedback', 'AccountController@feedback');
        //for testing
        Route::get('customers/{customers}/update-request', 'AccountController@update');

        Route::get('customers/{customers}/config', 'CustomersController@getSettings');
        Route::post('customers/{customers}/config', 'CustomersController@storeSettings');
        Route::put('customers/{customers}/password', 'Auth\APIPasswordController@update');
    });

    Route::get('account-status', 'CustomersController@accountStatus');
    Route::controller('auth', 'Auth\APIAuthController');
});

Route::get('process-trans/{customers}', 'ProcessTransactionController@index');


/*
|------------------------Ads Manager Route---------------------|
*/
Route::get('/', function () {
    return redirect('auth/login');
});

Route::group(['middleware' => 'adsManager'], function () {

    Route::get('manager/', function () {
        return redirect('manager/ads/promotions');
    });
    //promotions
    Route::get('manager/ads/promotions', ['as' => 'promotions.manager-manage', 'uses' => 'AdsController@managePromotions']);
    Route::get('manager/ads/promotions/create', ['as' => 'promotions.create', 'uses' => 'AdsController@createPromotion']);
    Route::get('ads/promotions/table', 'AdsController@promotionsTable');
    Route::get('manager/ads/promotions/{ads}/edit', ['as' => 'ads.edit', 'uses' => 'AdsController@edit']);
    Route::post('ads/promotions', ['as' => 'promotions.store', 'uses' => 'AdsController@storePromotion']);
    Route::put('ads/promotions/{ads}', ['as' => 'promotions.update', 'uses' => 'AdsController@updatePromotion']);
    Route::delete('ads', ['as' => 'ads.deleteMulti', 'uses' => 'AdsController@deleteMulti']);

    //targeted ads
    Route::get('manager/ads/targeted', ['as' => 'targeted.manager-manage', 'uses' => 'TargetedAdsController@manageTargeted']);
    Route::get('manager/ads/targeted/create', ['as' => 'targeted.create', 'uses' => 'TargetedAdsController@createTargeted']);
    Route::get('manager/ads/targeted/{ads}/edit', ['as' => 'ads.edit', 'uses' => 'TargetedAdsController@edit']);
    Route::get('ads/targeted/table', 'TargetedAdsController@targetedTable');
    Route::post('ads/targeted', ['as' => 'targeted.store', 'uses' => 'TargetedAdsController@storeTargeted']);
    Route::put('ads/targeted/{ads}', ['as' => 'targeted.update', 'uses' => 'TargetedAdsController@updateTargeted']);


    //change pass
    Route::get('manager/password/edit', 'Auth\PortalPasswordController@managerEdit');
    Route::put('manager/password', 'Auth\PortalPasswordController@update');
});

/*
|------------------------Admin Route---------------------|
*/
Route::group(['middleware' => 'admin'], function () {
    Route::get('admin', function () {
        return redirect('admin/minors');
    });
    //minors
    Route::get('admin/minors', 'MinorsController@manage');
    Route::resource('admin/minors', 'MinorsController', ['only' => ['store','show','create','edit','update']]);
    Route::get('minors/table', 'MinorsController@table');
    Route::delete('minors', ['as' => 'minors.deleteMulti', 'uses' => 'MinorsController@deleteMulti']);
    Route::get('minors/errors', 'MinorsController@errors');
    //majors
    Route::get('admin/majors', ['as' => 'majors.manage', 'uses' => 'MajorsController@manage']);
    Route::get('majors/table', 'MajorsController@table');
    Route::resource('majors', 'MajorsController', ['only' => ['store', 'create', 'edit', 'update']]);
    Route::delete('majors', ['as' => 'majors.deleteMulti', 'uses' => 'MajorsController@deleteMulti']);

    //change pass
    Route::get('admin/password/edit', 'Auth\PortalPasswordController@adminEdit');
    Route::put('admin/password', 'Auth\PortalPasswordController@update');

    //system setting
    Route::get('admin/system', function () {
        return redirect('admin/system/settings');
    });
    Route::get('admin/system/settings',
        ['as' => 'system.settings', 'uses' => 'SystemConfigController@getSettings']);
    Route::put('admin/system/settings',
        ['as' => 'system.settings.update-threshold', 'uses' => 'SystemConfigController@updateThreshold']);
    //cats select
    Route::get('admin/system/settings/category',
        ['as' => 'system.settings.category', 'uses' => 'ProcessTransactionController@getListCategories']);
    Route::post('admin/system/settings/category',
        ['as' => 'system.settings.category', 'uses' => 'ProcessTransactionController@selectCategories']);
    //admin tools
    Route::get('admin/system/tools',
        ['as' => 'system.tools', 'uses' => 'SystemConfigController@getTools']);
    //update taxonomy
    Route::post('taxonomy/update-requests',
        ['as' => 'taxonomy.update-requests.process', 'uses' => 'CategoriesController@updateTaxonomy']);
    Route::get('taxonomy/update-status', ['as' => 'taxonomy.update-status', 'uses' => 'CategoriesController@updateTaxonomyStatus']);
    //update store
    Route::post('stores/update-requests',
        ['as' => 'stores.update-requests.process', 'uses' => 'StoresController@updateStores']);
    Route::get('stores/update-status', ['as' => 'stores.update-status', 'uses' => 'StoresController@updateStoresStatus']);

    Route::get('admin/system/area-config',
        ['as' => 'system.area-config', 'uses' => 'ProcessTransactionController@getAreaConfig']);
});

/*
|------------------------Others Route---------------------|
*/
Route::get('ads/thumbnail/{ads}', 'AdsController@thumbnail');
Route::get('ads/{ads}/preview', 'AdsController@preview');
Route::get('ads/{ads}/', ['as' => 'ads.show', 'uses' => 'AdsController@show']);

Route::controllers([
    'auth' => 'Auth\PortalAuthController',
]);


