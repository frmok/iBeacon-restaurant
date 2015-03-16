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

Route::get('admin', 'Admin\AdminController@login');

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

//Mobile API
Route::any('mobile/createUser', 'APIController@createUser');
Route::any('mobile/userLogin', 'APIController@userLogin');
Route::any('mobile/createBillByTable', 'APIController@createBillByTable');
Route::get('mobile/getTableByBecaon/{major}/{minor}', 'APIController@getTableByBecaon');
Route::get('mobile/items/', 'APIController@itemList');
Route::get('mobile/item/{id}', 'APIController@itemDetail');
Route::get('mobile/categories/', 'APIController@categoryList');
Route::post('mobile/orderItem', 'APIController@orderItem');
Route::get('mobile/bill/{id}', 'APIController@billDetail');
Route::post('mobile/payBill', 'APIController@payBill');
Route::get('mobile/queues', 'APIController@queues');
Route::get('mobile/amountToPay/{id}', 'APIController@amountToPay');
Route::get('mobile/enqueue/{people}', 'APIController@enqueue');

Route::post('mobile/ticket', 'APIController@ticketUpdate');
Route::get('mobile/orderHistory', 'APIController@orderHistory');

//Advertisement API
Route::get('mobile/getAdvertisement', 'APIController@getAdvertisement');



//backend api
Route::group(['middleware' => 'checkcred'], function()
{  
    //Ticket API
    Route::post('api/ticket', 'Admin\TicketController@ticketUpdate');

    //Queue Type API
    Route::get('api/QueueType', 'Admin\QueueTypeController@index');
    Route::get('api/QueueType/{id}', 'Admin\QueueTypeController@detail');
    Route::get('api/clearQueue/{id}', 'Admin\QueueTypeController@clearQueue');

    //Order API
    Route::get('api/order', 'Admin\OrderController@index');
    Route::get('api/order/{id}', 'Admin\OrderController@detail');
    Route::post('api/order', 'Admin\OrderController@update');

    //Table API
    Route::get('api/table', 'Admin\TableController@index');
    Route::get('api/table/{id}', 'Admin\TableController@detail');
    Route::post('api/table/add', 'Admin\TableController@add');
    Route::post('api/table', 'Admin\TableController@update');
    Route::post('api/table/delete', 'Admin\TableController@delete');

    //Bill API
    Route::get('api/bill', 'Admin\BillController@index');
    Route::get('api/bill/{id}', 'Admin\BillController@detail');

    //Category API
    Route::get('api/categories/', 'Admin\CategoryController@index');
    Route::get('api/category/{id}', 'Admin\CategoryController@detail');
    Route::post('api/category/add', 'Admin\ItemController@add');
    Route::post('api/category', 'Admin\CategoryController@update');
    Route::post('api/category/delete', 'Admin\CategoryController@delete');

    //Item API
    Route::get('api/items/', 'Admin\ItemController@index');
    Route::get('api/item/{id}', 'Admin\ItemController@detail');
    Route::post('api/item', 'Admin\ItemController@update');
    Route::post('api/item/add', 'Admin\ItemController@add');
    Route::post('api/item/delete', 'Admin\ItemController@delete');

    //Ticket API
    Route::get('api/waitingPeople/{id}', 'Admin\TicketController@waitingPeople');
    Route::get('api/avgWaitingTime/{id}', 'Admin\TicketController@avgWaitingTime');
    Route::get('api/currentTicket/{id}', 'Admin\TicketController@currentTicket');
});


Route::get('api/test', 'APIController@test');