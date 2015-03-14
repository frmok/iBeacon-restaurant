<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\QueueType;
use App\Ticket;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class QueueTypeController extends Controller {
/*
    public function index($type)
    {
        $queue = QueueType::find($type);
        $queueName = $queue->name;
        $queueType = $queue->id;
        $tickets = $queue->tickets;
        return view('admin.queue_type_index')->with('tickets', $tickets)->with('name', $queueName)->with('type', $queueType);
    }
*/

    /**
    * Return all queue types.
    *
    * @return array
    */
    public function index(){
        return QueueType::all();
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
    * Clear all the tickets of a particular queue
    *
    * @param  int $id  
    * @return void
    */
    public function clearQueue($id){
        Ticket::where('queue_type_id', $id)->update(array('cleared' => 1));
    }
}