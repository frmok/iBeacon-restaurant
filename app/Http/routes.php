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

// Route::get('/', 'WelcomeController@index');

// Route::get('home', 'HomeController@index');

// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);

Route::group(['middleware' => 'csrf'], function()
{

});
Route::get('admin', 'Admin\AdminController@login');
Route::post('admin', 'Admin\AdminController@checkLogin');

//table route
Route::get('admin/table', 'Admin\TableController@index');
Route::get('admin/table/detail/{id?}', 'Admin\TableController@detail');
Route::get('admin/table/delete/{id}', 'Admin\TableController@delete');
Route::post('admin/table/create', 'Admin\TableController@create');
Route::post('admin/table/update', 'Admin\TableController@update');

//category route
Route::get('admin/category', 'Admin\CategoryController@index');
Route::get('admin/category/detail/{id?}', 'Admin\CategoryController@detail');
Route::get('admin/category/delete/{id}', 'Admin\CategoryController@delete');
Route::post('admin/category/create', 'Admin\CategoryController@create');
Route::post('admin/category/update', 'Admin\CategoryController@update');

//item route
Route::get('admin/item', 'Admin\ItemController@index');
Route::get('admin/item/detail/{id?}', 'Admin\ItemController@detail');
Route::get('admin/item/delete/{id}', 'Admin\ItemController@delete');
Route::post('admin/item/create', 'Admin\ItemController@create');
Route::post('admin/item/update', 'Admin\ItemController@update');

//bill
Route::get('admin/bill', 'Admin\BillController@index');
Route::get('admin/bill/detail/{id?}', 'Admin\BillController@detail');
Route::get('admin/bill/delete/{id}', 'Admin\BillController@delete');

//order
Route::get('admin/order', 'Admin\OrderController@index');
Route::get('admin/order/delete/{id}', 'Admin\OrderController@delete');
Route::get('admin/order/detail/{id}', 'Admin\OrderController@detail');
Route::post('admin/order/update', 'Admin\OrderController@update');

//stat
Route::get('admin/stats', 'Admin\StatController@index');
Route::get('stat/ajax_best_selling_item', 'Admin\StatController@ajax_best_selling_item');
Route::get('stat/ajax_profit', 'Admin\StatController@ajax_profit');

//API
Route::any('api/createUser', 'APIController@createUser');
Route::any('api/userLogin', 'APIController@userLogin');
Route::get('api/getTableByBecaon/{major}/{minor}', 'APIController@getTableByBecaon');
Route::get('api/bill/{id}', 'APIController@billDetail');
Route::get('api/items/', 'APIController@itemList');
Route::get('api/categories/', 'APIController@categoryList');
Route::get('api/item/{id}', 'APIController@itemDetail');
Route::post('api/payBill', 'APIController@payBill');
Route::post('api/orderItem', 'APIController@orderItem');
Route::post('api/order', 'APIController@orderUpdate');
Route::any('api/createBillByTable', 'APIController@createBillByTable');
Route::get('api/orderHistory', 'APIController@orderHistory');

//test...
Route::get('api/test', 'APIController@test');
