<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Order;
use App\Item;
use App\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller {
/*
    public function index()
    {
        $orders = Order::with('item', 'bill.table')->orderBy('created_at', 'DESC')->get();
        return view('admin.order_index')->with('orders', $orders);
    }

    public function detail($id = NULL)
    {
        $items = Item::all();
        $itemOptions = array();
        foreach ($items as $item) {
            $itemOptions[$item->id] = $item->item_name;
        }
        if(isset($id)){
            $item = Item::find($id);
            $action = 'update';
        }else{
            $item = new Item();
            $action = 'create';
        }
        $order = Order::with('item', 'bill.table')->find($id);
        return view('admin.order_detail')->with('order', $order)->with('action', $action)->with('itemOptions', $itemOptions)->with('statusOptions', Order::$statusText);
    }

    public function update(Request $request)
    {
        $items = Item::all();
        $itemOptions = array();
        foreach ($items as $item) {
            $itemOptions[$item->id] = $item->item_name;
        }
        $order = Order::find($request->get('id'));
        $order->fill($request->all());
        $order->save();
        return view('admin.order_detail')->with('order', $order)->with('action', 'update')->with('itemOptions', $itemOptions)->with('statusOptions', Order::$statusText)->with('msg', 'Updated successfully');
    }
*/

    /**
    * Return all orders.
    *
    * @return array
    */
    public function index(){
        $orders = Order::with('item', 'bill.table')->orderBy('created_at', 'DESC')->get();
        return $orders;
    }

    /**
    * Return the data of a specific order.
    *
    * @param  int $id  
    * @return Order
    */
    public function detail($id){
        $order = Order::with('item', 'bill.table')->find($id);
        return $order;
    }

    /**
    * Update the order with submitted data and return the updated data.
    *
    * @param  Request $request
    * @return Order
    */
    public function update(Request $request){
        $order = Order::find($request->get('id'));
        $order->fill($request->all());
        $order->save();
        return $order;
    }
}