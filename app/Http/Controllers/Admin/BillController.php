<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Bill;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BillController extends Controller {
/*
    public function index()
    {
        $bills = Bill::with('table', 'orders', 'orders.item')->orderBy('created_at', 'desc')->get();
        return view('admin.bill_index')->with('bills', $bills);
    }

    public function detail($id = NULL)
    {
        $bill = Bill::with('table', 'orders', 'orders.item')->find($id);
        $bill->amount = $bill->tempAmount();
        return view('admin.bill_detail')->with('bill', $bill);
    }
*/

    public function index(){
        return $bills = Bill::with('table', 'orders', 'orders.item')->orderBy('created_at', 'desc')->get();
    }

    public function detail($id){
        $bill = Bill::with('table', 'orders', 'orders.item')->find($id);
        $bill->formattedTime = date('d M Y', strtotime($bill->created_at));
        return $bill;
    }

}