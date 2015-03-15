<?php namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller {

    public function login()
    {
        return view('admin.login');
    }

    public function checkLogin(Request $request)
    {
        if (Auth::attempt(array('email' => $request->get('email'), 'password' => $request->get('password'))))
        {
            if(Auth::user()->right != 1){
                return view('admin.login')->with('wrong', true);
            }
            return redirect()->to('admin/table');
        }else{
            return view('admin.login')->with('wrong', true);
        }
    }
}