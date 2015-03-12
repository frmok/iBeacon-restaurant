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

    public function index(){
        return QueueType::all();
    }

    public function detail($id){
        $queueType = QueueType::find($id);
        $tickets = $queueType->tickets;
        $response = array();
        $response['queue'] = $queueType;
        return \Response::json($response);
    }
}