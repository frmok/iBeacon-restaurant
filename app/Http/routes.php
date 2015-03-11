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

//ticket route
Route::get('admin/queueType/{type}', 'Admin\QueueTypeController@index');


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
Route::post('api/payBill', 'APIController@payBill');
Route::post('api/orderItem', 'APIController@orderItem');
Route::post('api/getTicket', 'APIController@getTicket');
Route::post('api/ticket', 'APIController@ticketUpdate');
Route::any('api/createBillByTable', 'APIController@createBillByTable');
Route::get('api/orderHistory', 'APIController@orderHistory');

//Order API
Route::get('api/order', 'APIController@order');
Route::get('api/order/{id}', 'APIController@orderDetail');
Route::post('api/order', 'APIController@orderUpdate');

//Queue Type API
Route::get('api/QueueType', 'APIController@QueueType');
Route::get('api/QueueType/{id}', 'APIController@QueueTypeDetail');

//Table API
Route::get('api/table', 'APIController@table');
Route::get('api/table/{id}', 'APIController@tableDetail');
Route::post('api/table/add', 'APIController@tableAdd');
Route::post('api/table', 'APIController@tableUpdate');
Route::post('api/table/delete', 'APIController@tableDelete');

//Bill API
Route::get('api/bill', 'APIController@bill');
Route::get('api/bill/{id}', 'APIController@billDetail');
Route::get('api/bill2/{id}', 'APIController@_billDetail');

//Category API
Route::get('api/categories/', 'APIController@categoryList');
Route::get('api/category/{id}', 'APIController@categoryDetail');
Route::post('api/category/add', 'APIController@categoryAdd');
Route::post('api/category', 'APIController@categoryUpdate');
Route::post('api/category/delete', 'APIController@categoryDelete');

//Item API
Route::get('api/items/', 'APIController@itemList');
Route::get('api/item/{id}', 'APIController@itemDetail');
Route::post('api/item/add', 'APIController@itemAdd');
Route::post('api/item', 'APIController@itemUpdate');
Route::post('api/item/delete', 'APIController@itemDelete');

//Advertisement API
Route::get('api/getAdvertisement', 'APIController@getAdvertisement');

//Ticket API
Route::get('api/currentTicket/{id}', 'APIController@currentTicket');
Route::get('api/waitingPeople/{id}', 'APIController@waitingPeople');
Route::get('api/avgWaitingTime/{id}', 'APIController@avgWaitingTime');

//test...
Route::get('api/test', 'APIController@test');
