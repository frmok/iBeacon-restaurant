<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Table;
use App\Bill;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TableController extends Controller {
/*
    public function index()
    {
        $tables = Table::all();
        return view('admin.table_index')->with('tables', $tables);
    }

    public function detail($id = NULL)
    {
        if(isset($id)){
            $table = Table::find($id);
            $action = 'update';
        }else{
            $table = new Table();
            $action = 'create';
        }
        return view('admin.table_detail')->with('table', $table)->with('action', $action);
    }

    public function create(Request $request)
    {
        $table = new Table();
        $table->fill($request->all());
        $table->save();
        return view('admin.table_detail')->with('table', $table)->with('action', 'update')->with('msg', 'Added successfully');
    }

    public function update(Request $request)
    {
        $table = Table::find($request->get('id'));
        $table->fill($request->all());
        $table->save();
        return view('admin.table_detail')->with('table', $table)->with('action', 'update')->with('msg', 'Updated successfully');
    }

    public function delete($id)
    {
        $table = Table::find($id);
        $table->delete();
        return redirect()->to('admin/table');

    }
*/

    public function index(){
        return Table::with(array('bills' => function ($query){
            $query->where('status', 0);
        }))->get();
    }

    public function detail($id){
        return Table::find($id);
    }

    public function add(Request $request){
        $table = new Table();
        $table->fill($request->all());
        $table->save();
        return $table;
    }

    public function update(Request $request){
        $table = Table::find($request->get('id'));
        $table->fill($request->all());
        $table->save();
        return $table;
    }

    public function delete(Request $request){
        $table = Table::find($request->get('id'));
        $table->delete();
    }
}