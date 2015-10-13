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

Route::group(['prefix'=>'api/v1'],function(){
	Route::get('customers/{customers}/context-ads/{majors}/{minors}','ContextAdsController@index');
	Route::get('ads','AdsController@index');
	Route::get('customers/{customers}/received-ads','AdsController@receivedIndex');
	Route::get('account-status','CustomersController@accountStatus');


	Route::post('customers/{customers}/update-request', 'AccountController@update');
	Route::get('customers/{customers}/update-request', 'AccountController@update');
	Route::controller('auth','Auth\APIAuthController');
});


Route::get('process-trans/category', 'ProcessTransactionController@getListCategories');
Route::post('process-trans/category', 'ProcessTransactionController@selectCategory');
//for testing
Route::get('process-trans/{customers}', 'ProcessTransactionController@index');



Route::controllers([
	'portal/auth' => 'Auth\PortalAuthController',
	'portal/password' => 'Auth\PasswordController',
]);

//portal
Route::get('/', 'WelcomeController@index');
Route::get('home', 'HomeController@index');
Route::get('manager', function () {
    return redirect('manager/ads');
});
Route::get('manager/ads', 'AdsController@manage');
Route::get('ads/table', 'AdsController@table');
Route::get('ads/{ads}/','AdsController@show');
Route::delete('ads/',['as'=>'ads.deleteMulti','uses'=>'AdsController@deleteMulti']);
Route::get('manager/ads/promotions/create', ['as'=>'promotions.create','uses'=>'AdsController@createPromotion']);
Route::post('ads/promotions', 'AdsController@storePromotion');
Route::get('manager/ads/{ads}/edit', 'AdsController@edit');
Route::put('manager/ads/{ads}', ['as'=>'promotions.update','uses'=>'AdsController@updatePromotion']);

Route::get('admin', function () {
	return redirect('admin/minors');
});
Route::get('admin/minors', 'MinorsController@manage');
