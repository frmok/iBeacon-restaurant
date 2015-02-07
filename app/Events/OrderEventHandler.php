<?php namespace App\Events;

class OrderEventHandler {

    public function onOrderCreate($order)
    {
        error_log('order create');
        $packet['action'] = 'order.create';
        $packet['order'] = $order;
        $packet['order']['bill'] = $order->bill->table;
        $packet['order']['item'] = $order->item;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function onOrderUpdate($order)
    {
        error_log('order update');
        $packet['action'] = 'order.update';
        $packet['order'] = $order;
        $packet['order']['status_text'] = $order->status_text;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));

        //for mobile bill update
        $packet = array();
        $packet['action'] = 'wsorder.refresh';
        $packet['billId'] = $order->bill->id;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));

        //if the bill is paid
        if($order->bill->outStandingBalance() == 0){
            $order->bill->status = 1;
            $order->bill->save();
            error_log('bill is paid');
        }
    }
    

    public function subscribe($events)
    {
        $events->listen('order.created', 'App\Events\OrderEventHandler@onOrderCreate');
        $events->listen('order.updated', 'App\Events\OrderEventHandler@onOrderUpdate');
    }

}