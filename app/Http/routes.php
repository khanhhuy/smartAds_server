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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

//Route::get('customers/{customers}/context-ads/{minors}','ContextAdsController@index');
Route::get('customers/{customers}/context-ads/{majors}/{minors}','ContextAdsController@index');
Route::get('ads/{ads}/','AdsController@show');
Route::get('ads','AdsController@index');
Route::get('customers/{customers}/received-ads','AdsController@receivedIndex');

Route::get('mining/category', 'MiningController@getListCategories');
Route::post('mining/category', 'MiningController@selectCategory');

//for testing
Route::get('mining/{customers}', 'MiningController@index');

Route::post('customers/{customers}/update-request', 'AccountController@update');

Route::get('account-status','CustomersController@accountStatus');
Route::controllers([
	'portal/auth' => 'Auth\PortalAuthController',
	'portal/password' => 'Auth\PasswordController',
]);
Route::controller('auth','Auth\APIAuthController');