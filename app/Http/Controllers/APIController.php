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
use App\Ticket;
use App\QueueType;
use App\Advertisement;

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

    public function order(){
        $orders = Order::with('item', 'bill.table')->orderBy('created_at', 'DESC')->get();
        return $orders;
    }

    public function orderDetail($id){
        $order = Order::with('item', 'bill.table')->find($id);
        return $order;
    }


    public function orderUpdate(Request $request){
        $order = Order::find($request->get('id'));
        $order->fill($request->all());
        $order->save();
        return $order;
    }

    public function itemList(Request $request){
        $catId = $request->get('catId');
        if(!$catId){
            return Item::all();
        }else{
            echo Item::where('category_id', $request->get('catId'))->get();
        }
    }

    public function itemDetail($id){
        echo Item::find($id);
    }

    public function itemAdd(Request $request)
    {
        $item = new Item();
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return $item;
    }

    public function itemUpdate(Request $request)
    {
        $item = Item::find($request->get('id'));
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return $item;
    }

    public function itemDelete(Request $request)
    {
        $item = Item::find($request->get('id'));
        $item->delete();
    }

    public function categoryList(){
        $categories = Category::all();
        foreach($categories as $category){
            $category['category_img'] = rawurlencode($category['category_img']);
        }
        echo $categories;
    }

    public function categoryDetail($id){
        return Category::find($id);
    }

    public function categoryAdd(Request $request)
    {
        $category = new Category();
        $category->fill($request->except('category_img'));
        if ($request->hasFile('category_img') === true) {
            $destinationPath = public_path() . Category::$img_path;
            $request->file('category_img')->move($destinationPath, $request->file('category_img')->getClientOriginalName());
            $category->category_img = $request->file('category_img')->getClientOriginalName();
        }
        $category->save();
        return $category;
    }

    public function categoryUpdate(Request $request)
    {
        $category = Category::find($request->get('id'));
        $category->fill($request->except('category_img'));
        if ($request->hasFile('category_img') === true) {
            $destinationPath = public_path() . Category::$img_path;
            $request->file('category_img')->move($destinationPath, $request->file('category_img')->getClientOriginalName());
            $category->category_img = $request->file('category_img')->getClientOriginalName();
        }
        $category->save();
        return $category;
    }

    public function categoryDelete(Request $request)
    {
        $category = Category::find($request->get('id'));
        $category->delete();
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


    public function getAdvertisement(Request $request){
        if($request->get('id')){
            $id = $request->get('id');
            return Advertisement::getMessage($id);
        }else{
            return Advertisement::getMessage();
        }
    }

    public function QueueType(){
        return QueueType::all();
    }

    public function QueueTypeDetail($id){
        $queueType = QueueType::find($id);
        $tickets = $queueType->tickets;
        $response = array();
        $response['queue'] = $queueType;
        return \Response::json($response);
    }

    public function table(){
        return Table::with(array('bills' => function ($query){
            $query->where('status', 0);
        }))->get();
    }

    public function tableDetail($id){
        return Table::find($id);
    }

    public function tableAdd(Request $request){
        $table = new Table();
        $table->fill($request->all());
        $table->save();
        return $table;
    }

    public function tableUpdate(Request $request){
        $table = Table::find($request->get('id'));
        $table->fill($request->all());
        $table->save();
        return $table;
    }

    public function tableDelete(Request $request){
        $table = Table::find($request->get('id'));
        $table->delete();
    }

    public function bill(){
        return $bills = Bill::with('table', 'orders', 'orders.item')->orderBy('created_at', 'desc')->get();
    }

    public function _billDetail($id){
        $bill = Bill::with('table', 'orders', 'orders.item')->find($id);
        $bill->formattedTime = date('d M Y', strtotime($bill->created_at));
        return $bill;
    }


    public function getTicket(Request $request){
        Ticket::enqueue(QueueType::typeByPeople($request->get('people')) ,$request->get('people'));
    }

    public function ticketUpdate(Request $request){
        $ticket = Ticket::find($request->get('id'));
        $ticket->fill($request->all());
        $ticket->save();
    }

    //test route...no usage
    public function test(){
        $table = Table::find(3);
        $table->table_status = 0;
        $table->save();
    }
}