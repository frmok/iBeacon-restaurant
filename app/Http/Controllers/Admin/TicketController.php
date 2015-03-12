<?php namespace App\Http\Controllers\Admin;

use App\Ticket;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller {

    
    public function currentTicket($id){
        $response['current'] = Ticket::currentTicket($id);
        return \Response::json($response);
    }

    public function waitingPeople($id){
        $response['waiting'] = Ticket::waitingPeople($id);
        return \Response::json($response);
    }

    //test route...no usage
    public function avgWaitingTime($id){
        $response = Ticket::avgWaitingTime($id)[0];
        return \Response::json($response);
    }

}