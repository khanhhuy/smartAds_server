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
Route::group(['prefix'=>'api/v1'],function(){
	Route::get('customers/{customers}/context-ads/{majors}/{minors}','ContextAdsController@index');
	Route::get('ads','AdsController@index');
	Route::get('customers/{customers}/received-ads','AdsController@receivedIndex');
	Route::get('account-status','CustomersController@accountStatus');

	Route::post('customers/{customers}/update-request', 'AccountController@update');
    Route::post('customers/{customers}/feedback', 'AccountController@feedback');
	//for testing
	Route::get('customers/{customers}/update-request', 'AccountController@update');

	Route::get('customers/{customers}/config', 'CustomersController@getSettings');
    Route::post('customers/{customers}/config', 'CustomersController@storeSettings');

	Route::controller('auth','Auth\APIAuthController');
});
Route::get('ads/thumbnail/{ads}', 'AdsController@thumbnail');
//for testing
Route::get('process-trans/{customers}', 'ProcessTransactionController@index');

/*
|------------------------Portal Route---------------------|
*/
Route::get('/', 'WelcomeController@index');
Route::get('home', 'HomeController@index');

Route::get('ads/promotions/table', 'AdsController@promotionsTable');
Route::get('ads/targeted/table', 'AdsController@targetedTable');
Route::get('ads/{ads}/','AdsController@show');

Route::get('manager/', function () {
    return redirect('manager/ads/promotions');
});
Route::get('manager/ads/promotions', ['as'=>'promotions.manager-manage','uses'=>'AdsController@managePromotions']);
Route::get('manager/ads/promotions/create', ['as'=>'promotions.create','uses'=>'AdsController@createPromotion']);
Route::get('manager/ads/targeted/showrules', 'TargetedAdsController@getRule');
Route::get('manager/ads/targeted', ['as'=>'targeted.manager-manage','uses'=>'TargetedAdsController@manageTargeted']);
Route::get('manager/ads/targeted/create', ['as'=>'targeted.create','uses'=>'TargetedAdsController@createTargeted']);
Route::get('manager/ads/{ads}/edit', ['as'=>'ads.edit','uses'=>'AdsController@edit']);

Route::post('ads/promotions', ['as'=>'promotions.store','uses'=>'AdsController@storePromotion']);
Route::post('ads/targeted', ['as'=>'targeted.store','uses'=>'TargetedAdsController@storeTargeted']);
Route::put('ads/promotions/{ads}', ['as'=>'promotions.update','uses'=>'AdsController@updatePromotion']);
Route::put('ads/promotions/{ads}', ['as'=>'targeted.update','uses'=>'TargetedAdsController@updateTargeted']);
Route::delete('ads',['as'=>'ads.deleteMulti','uses'=>'AdsController@deleteMulti']);

/*
|------------------------Admin Route---------------------|
*/
Route::get('admin', function () {
	return redirect('admin/minors');
});
Route::get('admin/minors', 'MinorsController@manage');
Route::get('admin/majors', 'MajorsController@manage');
Route::get('majors/table', 'MajorsController@table');
Route::resource('majors', 'MajorsController',['only'=>['store']]);
Route::delete('majors',['as'=>'majors.deleteMulti','uses'=>'MajorsController@deleteMulti']);
Route::get('admin/category', 'ProcessTransactionController@getListCategories');
Route::post('admin/category', 'ProcessTransactionController@selectCategory');

/*
|------------------------Others Route---------------------|
*/
Route::controllers([
    'portal/auth' => 'Auth\PortalAuthController',
    'portal/password' => 'Auth\PasswordController',
]);

