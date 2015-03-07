<?php namespace App\Events;
use App\Table;
class BillEventHandler {

    public function onBillCreate($bill)
    {
        error_log('bill created');
        $packet['action'] = 'bill.create';
        $packet['bill'] = $bill;
        //lock the table once a bill is created
        $table = Table::find($bill->table->id);
        $table->table_status = 1;
        $table->save();
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function onBillUpdate($bill)
    {
        error_log('bill updated');
        $packet['action'] = 'bill.update';
        $packet['bill'] = $bill;
        if($bill->status == 1){
            //release the table when the bill is paid
            $table = Table::find($bill->table->id);
            $table->table_status = 0;
            $table->save();
        }
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function subscribe($events)
    {
        $events->listen('bill.created', 'App\Events\BillEventHandler@onBillCreate');
        $events->listen('bill.updated', 'App\Events\BillEventHandler@onBillUpdate');
    }

}