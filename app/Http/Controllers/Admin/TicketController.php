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
        //ticet save should be done before sending notificaitons
        $ticket->save();
        //send notification if status is changed to 'dequeued' state
        if($request->get('ticket_status') == 1){
            //send notification to the next five numbers
            $tickets = Ticket::where('cleared', 0)->where('queue_type_id', $ticket->queue_type_id)->where('ticket_number', '>', $ticket->ticket_number)->take(5)->get();
            $data = [];
            $data['message'] = "The current number is ".$ticket->ticket_number;
            $identifiers = [];
            foreach($tickets as $tempTicket){
                array_push($identifiers, $tempTicket->identifier);
            }
            $data['identifiers'] = $identifiers;
            $data['type'] = 'dequeue';
            $data = json_encode($data);
            \Push::sendNotification($data);
            //send notification to the current number
            $data = [];
            $data['message'] = "This is your number now.";
            $data['identifiers'] = [$ticket->identifier];
            $data['type'] = 'dequeue';
            $data = json_encode($data);
            \Push::sendNotification($data);
        }
        return $ticket;
    }

}