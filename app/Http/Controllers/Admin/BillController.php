<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Bill;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BillController extends Controller {
    /**
    * Return all bills.
    *
    * @return array
    */
    public function index(){
        return $bills = Bill::with('table', 'orders', 'orders.item')->orderBy('created_at', 'desc')->get();
    }
    
    /**
    * Return the data of a specific bill.
    *
    * @param  int $id
    * @return Bill
    */
    public function detail($id){
        $bill = Bill::with('table', 'orders', 'orders.item')->find($id);
        $bill->formattedTime = date('d M Y', strtotime($bill->created_at));
        return $bill;
    }

}