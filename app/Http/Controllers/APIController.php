<?php namespace App\Http\Controllers;

use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Table;
use App\Bill;
use App\Item;
use App\Order;

class APIController extends Controller {

    public function getTableByBecaon($major, $minor){
        $table = Table::where('major',$major)->where('minor',$minor)->first();
        echo $table;
    }

    public function billDetail($id){
        $bill = Bill::with('table','orders','orders.item')->find(2);
        $bill->amount = $bill->tempAmount();
        echo $bill;
    }

    public function itemList(){
        echo Item::all();
    }
    public function itemDetail($id){
        echo Item::find($id);
    }
}