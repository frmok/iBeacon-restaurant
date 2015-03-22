<?php namespace App\Http\Controllers\Admin;

use App\Ticket;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller {
    /**
    * Return the current ticket number of a particular queue type
    *
    * @param int $id
    * @return Response
    */
    public function currentTicket($id){
        $response['current'] = Ticket::currentTicket($id);
        return \Response::json($response);
    }
    
    /**
    * Return the number of people waiting of a particular queue type
    *
    * @param int $id
    * @return Response
    */
    public function waitingPeople($id){
        $response['waiting'] = Ticket::waitingPeople($id);
        return \Response::json($response);
    }

    /**
    * Return the average waiting time of a particular queue type
    *
    * @param int $id
    * @return Response
    */
    public function avgWaitingTime($id){
        $response = Ticket::avgWaitingTime($id)[0];
        return \Response::json($response);
    }

    /**
    * Update the ticket with submitted data and return the updated data.
    *
    * @param int $id
    * @return Response
    */
    public function ticketUpdate(Request $request){
        $ticket = Ticket::find($request->get('id'));
        $ticket->fill($request->all());
        $ticket->save();
        return $ticket;
    }

}