<?php 
namespace App\Http\Controllers;

use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Events\Dispatcher;
use App\Table;
use App\Bill;
use App\Item;
use App\Order;
use App\Category;

class APIController extends Controller {

    public function getTableByBecaon($major, $minor){
        $table = Table::where('major',$major)->where('minor',$minor)->first();
        echo $table;
    }

    public function createBillByTable(Request $request){
        $bill = Bill::where('table_id', $request->get('id'))->where('status', 0)->get();
        if(count($bill) === 0){
            //create bill
            $bill = new Bill();
            $bill->table_id = $request->get('id');
            $bill->status = 0;
            $bill->save();
            $bill->id = "$bill->id";
            echo $bill->toJson();
        }else{
            echo $bill->first();
        }
    }

    public function orderUpdate(Request $request){
        $order = Order::find($request->get('id'));
        $order->fill($request->all());
        $order->save();
    }

    public function itemList(Request $request){
        echo Item::where('category_id', $request->get('catId'))->get();
    }

    public function categoryList(){
        $categories = Category::all();
        foreach($categories as $category){
            $category['category_img'] = rawurlencode($category['category_img']);
        }
        echo $categories;
    }

    public function itemDetail($id){
        echo Item::find($id);
    }

    public function orderItem(Request $request){
        if($request->get('billId') > 0){
            $order = new Order();
            $order->bill_id = $request->get('billId');
            $order->item_id = $request->get('itemId');
            $order->quantity = $request->get('quantity');
            $order->save();
        }
    }


    public function billDetail($id){
        $bill = Bill::with(array('table', 'orders' => function($query){
            $query->where('order_status', '!=' ,3);

        }, 'orders.item'))->find($id);
        $bill->amount = $bill->tempAmount();
        echo $bill;
    }

    public function payBill(Request $request){
        $itemsArray = explode(',', $request->get('orders'));
        foreach ($itemsArray as $item){
            $order = Order::find($item);
            $order->order_status = 3;
            $order->save();
        }
    }

    public function test(){
        $order = Order::find(1);
        $order->order_status = 3;
        $order->save();
    }
}