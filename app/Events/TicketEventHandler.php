<?php namespace App\Events;
use App\Table;
class TicketEventHandler {

    public function onTicketCreate($ticket)
    {
        error_log('ticket created');
        $packet['action'] = 'ticket.create';
        $packet['ticket'] = $ticket;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }

    public function onTicketUpdate($ticket)
    {
        error_log('ticket updated');
        $packet['action'] = 'ticket.update';
        $packet['ticket'] = $ticket;
        $packet['ticket']['status_text'] = $ticket->status_text;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function subscribe($events)
    {
        $events->listen('ticket.created', 'App\Events\TicketEventHandler@onTicketCreate');
        $events->listen('ticket.updated', 'App\Events\TicketEventHandler@onTicketUpdate');
    }

}