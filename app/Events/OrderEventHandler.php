<?php namespace App\Events;

class OrderEventHandler {

    public function onOrderCreate($order)
    {
        error_log('create');
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
        error_log('update');
        $packet['action'] = 'order.update';
        $packet['order'] = $order;
        $packet['order']['status_text'] = $order->status_text;
        $context = new \ZMQContext(1, false);
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".env('ZEROMQ_PORT'));
        $socket->send(json_encode($packet));
    }
    

    public function subscribe($events)
    {
        $events->listen('order.created', 'App\Events\OrderEventHandler@onOrderCreate');
        $events->listen('order.updated', 'App\Events\OrderEventHandler@onOrderUpdate');
    }

}