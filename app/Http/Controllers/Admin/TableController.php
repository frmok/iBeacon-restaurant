<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Table;
use App\Bill;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TableController extends Controller {
    /**
    * Return all tables.
    *
    * @return array
    */
    public function index(){
        return Table::with(array('bills' => function ($query){
            $query->where('status', 0);
        }))->get();
    }
    
    /**
    * Return the data of a specific Table.
    *
    * @param  int $id  
    * @return Table
    */
    public function detail($id){
        return Table::find($id);
    }
    
    /**
    * Create a new table with the submitted data and return the new table data.
    *
    * @param  Request $request
    * @return Table
    */
    public function add(Request $request){
        $table = new Table();
        $table->fill($request->all());
        $table->save();
        return $table;
    }
    
    /**
    * Update the table with submitted data and return the updated data.
    *
    * @param  Request $request
    * @return Table
    */
    public function update(Request $request){
        $table = Table::find($request->get('id'));
        $table->fill($request->all());
        $table->save();
        return $table;
    }
    
    /**
    * Delete a specific bill
    *
    * @param  Request $request
    * @return void
    */
    public function delete(Request $request){
        $table = Table::find($request->get('id'));
        $table->delete();
        $response = array();
        $response['status'] = 200;
        $response['debug'] = 'Table deleted';
        return \Response::json($response);
    }
}