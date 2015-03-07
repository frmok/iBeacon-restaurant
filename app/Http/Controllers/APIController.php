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
use App\User;

class APIController extends Controller {

    //create user
    public function createUser(Request $request){
        $user = new User();
        $user->lastname = $request->get('lastname');
        $user->firstname = $request->get('firstname');
        $user->email = $request->get('email');
        $user->password = \Hash::make($request->get('password'));
        $user->right = 3;
        $user->save();
        return $user;
    }

    //login user by email and password
    public function userLogin(Request $request){
        if(Auth::attempt(array('email' => $request->get('email'), 'password' => $request->get('password')))){
            $packet = array();
            $packet['status'] = 200;
            $packet['debug'] = 'Login succeeed.';
            $packet['user'] = Auth::user();
            return \Response::json($packet);
        }else{
            $packet = array();
            $packet['status'] = 500;
            $packet['debug'] = 'Login fails';
            return \Response::json($packet);
        }
    }

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
            $order->user_id = $request->get('payer');
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

    public function orderHistory(Request $request){
        return Order::with('item')->where('user_id', $request->get('memberID'))->orderBy('created_at', 'desc')->get();
    }

    //test route...no usage
    public function test(){
        return Order::with('item')->where('user_id', 7)->orderBy('created_at', 'desc')->get();
    }
}