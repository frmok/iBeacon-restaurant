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
}