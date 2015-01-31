<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Bill;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BillController extends Controller {

    public function index()
    {
        $bills = Bill::with('table', 'orders', 'orders.item')->get();
        return view('admin.bill_index')->with('bills', $bills);
    }

    public function detail($id = NULL)
    {
        $bill = Bill::with('table', 'orders', 'orders.item')->find($id);
        $bill->amount = $bill->tempAmount();
        return view('admin.bill_detail')->with('bill', $bill);
    }

}