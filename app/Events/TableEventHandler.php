<?php namespace App\Events;
use App\Table;
class TableEventHandler {

    public function onTableCreate($table)
    {
        error_log('table created');
        $packet['action'] = 'table.create';
        $packet['table'] = $table;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function onTableUpdate($table)
    {
        error_log('table updated');
        $packet['action'] = 'table.update';
        $packet['table'] = $table;
        $packet['bill'] = Table::find(3)->bills->where('status', 0)->first();
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function subscribe($events)
    {
        $events->listen('table.created', 'App\Events\TableEventHandler@onTableCreate');
        $events->listen('table.updated', 'App\Events\TableEventHandler@onTableUpdate');
    }

}