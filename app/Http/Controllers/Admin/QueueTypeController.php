<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\QueueType;
use App\Ticket;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class QueueTypeController extends Controller {
    
    /**
    * Return all queue types.
    *
    * @return array
    */
    public function index(){
        return QueueType::orderBy('capacity')->get();
    }
    
    /**
    * Return the data of a specific queue type (with tickets that are not cleared).
    *
    * @param  int $id  
    * @return Response
    */
    public function detail($id){
        $queueType = QueueType::with(['tickets' => 
            function($query){ 
                $query->where('cleared',0);
            }])
        ->find($id);
        $response = array();
        $response['queue'] = $queueType;
        return \Response::json($response);
    }

    /**
    * Create a new queue type with the submitted data and return the new queue type data.
    *
    * @param  Request $request
    * @return QueueType
    */
    public function add(Request $request){
        $queueType = new QueueType();
        $queueType->fill($request->all());
        $queueType->save();
        $response = array();
        $response['queue'] = $queueType;
        return \Response::json($response);
    }

    /**
    * Update the queue type with submitted data and return the updated data.
    *
    * @param  Request $request
    * @return Item
    */
    public function update(Request $request){
        $queueType = QueueType::find($request->get('id'));
        $queueType->fill($request->all());
        $queueType->save();
        $response = array();
        $response['queue'] = $queueType;
        return \Response::json($response);
    }

    /**
    * Clear all the tickets of a particular queue
    *
    * @param  int $id  
    * @return void
    */
    public function clearQueue($id){
        //we do a dirty way of updating here
        \DB::table('ticket')
        ->update(['cleared' => 1]);
    }

    /**
    * Delete a specific queue type
    *
    * @param  Request $request
    * @return void
    */
    public function delete(Request $request)
    {
        $queueType = QueueType::find($request->get('id'));
        $queueType->delete();
    }
}