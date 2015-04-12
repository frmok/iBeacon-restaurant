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

    /**
    * Create a user with the submitted data
    *
    * @param  Request $request
    * @return User
    */
    public function createUser(Request $request){
        $user = User::where('email', $request->get('email'))->get();
        //found duplicated email...
        if(count($user) > 0){
            $packet = array();
            $packet['status'] = 500;
            $packet['debug'] = 'Duplicated email';
            return \Response::json($packet, 401);
        }
        $user = new User();
        $user->lastname = $request->get('lastname');
        $user->firstname = $request->get('firstname');
        $user->email = $request->get('email');
        $user->password = \Hash::make($request->get('password'));
        $user->right = 3;
        $user->save();
        return $user;
    }

    /**
    * Login a user by email and password
    *
    * @param  Request $request
    * @return Response
    */
    public function userLogin(Request $request){
        if(Auth::attempt(array('email' => $request->get('email'), 'password' => $request->get('password')))){
            //generate new token!!!
            $token = array(
                "iat" => time(),
                "exp" => time()+3600*24*24,
                "uid" => intVal(Auth::id())
                );
            $jwt = \JWT::encode($token, env('JWT_KEY'));
            $packet = array();
            $packet['status'] = 200;
            $packet['debug'] = 'Login succeed.';
            $packet['user'] = Auth::user();
            $packet['token'] = $jwt;
            return \Response::json($packet);
        }else{
            $packet = array();
            $packet['status'] = 401;
            $packet['debug'] = 'Login fails';
            return \Response::json($packet, 401);
        }
    }

    /**
    * Find a table by iBeacon major and minor
    *
    * @param  int $major
    * @param  int $minor
    * @return Table
    */
    public function getTableByBecaon($major, $minor){
        $table = Table::where('major',$major)->where('minor',$minor)->first();
        return $table;
    }

    /**
    * Create a bill (if not exist) and return it.
    *
    * @param  Request $request
    * @return Bill
    */
    public function createBillByTable(Request $request){
        $table = Table::find($request->get('id'));
        //no table is found...
        if(count($table) == 0){
            $packet = array();
            $packet['status'] = 500;
            $packet['debug'] = 'No table found';
            return \Response::json($packet, 500);
        }
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

    /**
    * Return a list of items of a particular category
    *
    * @param  Request $request
    * @return array
    */
    public function itemList(Request $request){
        $catId = $request->get('catId');
        if(!$catId){
            return Item::all();
        }else{
            echo Item::where('category_id', $request->get('catId'))->get();
        }
    }

    /**
    * Return the data of a particular item
    *
    * @param  int $id
    * @return Item
    */
    public function itemDetail($id){
        return Item::find($id);
    }

    /**
    * Return all the categories
    *
    * @return array
    */
    public function categoryList(){
        $categories = Category::all();
        foreach($categories as $category){
            $category['category_img'] = rawurlencode($category['category_img']);
        }
        return $categories;
    }

    /**
    * Place a order according to the submitted data
    *
    * @param  Request $request
    * @return void
    */
    public function orderItem(Request $request){
        $payer = 0; //by default
        $token = $request->get('token');
        if($token){
            $decoded = \JWT::decode($token, env('JWT_KEY'));
            \Auth::loginUsingId($decoded->uid);
            $payer = Auth::id(); //set the payer to the user id of the token
        }
        $bill = Bill::find($request->get('billId'));
        if(count($bill) <= 0){
            $packet = array();
            $packet['status'] = 500;
            $packet['debug'] = 'No bill found';
            return \Response::json($packet, 500);
        }
        $item = Item::find($request->get('itemId'));
        if(count($item) <= 0){
            $packet = array();
            $packet['status'] = 500;
            $packet['debug'] = 'No item found';
            return \Response::json($packet, 500);
        }
        if($request->get('billId') > 0){
            $order = new Order();
            $order->order_status = 0;
            $order->user_id = $payer;
            $order->bill_id = $request->get('billId');
            $order->item_id = $request->get('itemId');
            $order->quantity = $request->get('quantity');
            $order->save();
            $packet = array();
            $packet['status'] = 200;
            $packet['debug'] = 'Order succeeds';
            return \Response::json($packet);
        }
    }

    /**
    * Get the detail of a particular bill.
    *
    * @param  int $id
    * @return Bill
    */
    public function billDetail($id){
        $bill = Bill::with(array('table', 'orders' => function($query){
            $query->where('order_status', '!=' ,3);
        }, 'orders.item'))->find($id);
        $bill->amount = $bill->tempAmount();
        return $bill;
    }

    /**
    * Mark a item as paid according to the request
    *
    * @param  Request $request
    * @return void
    */
    public function payBill(Request $request){
        $itemsArray = explode(',', $request->get('orders'));
        foreach ($itemsArray as $item){
            $order = Order::find($item);
            $order->order_status = 3;
            $order->save();
        }
        $packet = array();
        $packet['status'] = 200;
        $packet['debug'] = 'Success payment';
        return \Response::json($packet);
    }

    /**
    * Return the items that a user ordered before
    *
    * @param  Request $request
    * @return array
    */
    public function orderHistory(Request $request){
        $token = $request->get('token');
        if($token){
            $decoded = \JWT::decode($token, env('JWT_KEY'));
            \Auth::loginUsingId($decoded->uid);
            $userID = Auth::id(); //set the payer to the user id of the token
        }
        return User::with(['orders.item', 'orders' => function($query){ $query->orderBy('created_at', 'DESC'); }])
        ->find($userID);
    }

    /**
    * Return a welcome message according to the member ID.
    *
    * @param  Request $request
    * @return Response
    */
    public function getAdvertisement(Request $request){
        if($request->get('id')){
            $id = $request->get('id');
            $message = Advertisement::getMessage($id);
        }else{
            $message = Advertisement::getMessage();
        }
        $response = [];
        $response['status'] = 200;
        $response['message'] = $message;
        return \Response::json($response);
    }

    public function ticketUpdate(Request $request){
        $ticket = Ticket::find($request->get('id'));
        $ticket->fill($request->all());
        $ticket->save();
    }

    /**
    * Return all queue types information
    *
    * @return array
    */
    public function queues($id = NULL){
        if($id){
            $queue = QueueType::find($id);
            if(count($queue) == 0){
                $response = [];
                $response['status'] = 500;
                $response['message'] = 'No queue type found';
                return \Response::json($response);
            }
            $queue['current'] = Ticket::currentTicket($queue->id);
            $queue['waiting'] = Ticket::waitingPeople($queue->id);
            return $queue;
        }else{
            $queues = QueueType::orderBy('capacity')->get();
            foreach ($queues as $queue) {
                $queue['current'] = Ticket::currentTicket($queue->id);
                $queue['waiting'] = Ticket::waitingPeople($queue->id);
            }
            return $queues;
        }
    }

    /**
    * Get the outstanding balance of a bill a particular bill.
    *
    * @param  int $id
    * @return Response
    */
    public function amountToPay($id){
        $bill = Bill::find($id);
        $response = [];
        $response['status'] = 200;
        $response['balance'] = $bill->outStandingBalance($id);
        return \Response::json($response);
    }

    /**
    * Create a ticket of a particular queue
    *
    * @param  int $people
    * @return Response
    */
    public function enqueue(Request $request){
        $type = QueueType::typeByPeople($request->get('people'));
        if($type === 0){
            $response = [];
            $response['status'] = 500;
            $response['debug'] = 'No suitable queue type';
            return \Response::json($response);
        }
        $identifier = str_replace(' ', '', $request->get('identifier'));
        $identifier = str_replace('<', '', $identifier);
        $identifier = str_replace('>', '', $identifier);
        $ticket = Ticket::enqueue($type, $request->get('people'), $identifier);
        return $ticket;
    }

    public function test(){
        return QueueType::typeByPeople(5);
    }
}