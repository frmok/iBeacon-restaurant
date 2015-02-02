<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
class wsBill{
    public $billId;
    public $customers;
    public $orders;
    public $customersOrders;

    public function __construct($billId, $customer){
        $this->billId = $billId;
        $this->customers = new \SplObjectStorage;
        $this->customers->attach($customer);
        $this->orders = array();
        $this->customersOrders = array();
    }
}
class Chat implements MessageComponentInterface {
    protected $clients;
    protected $admins;
    protected $wsBills;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->admins = new \SplObjectStorage;
        $this->wsBills = array();
    }

    public function onQueue($entry) {
        foreach ($this->clients as $client) {
            // The sender is not the receiver, send to each client connected
            $client->send($entry);
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $json = json_decode($msg, true);
        //check bill existence
        $found = 0;
        if(count($this->wsBills) > 0){
            foreach($this->wsBills as $wsBill){
                if($wsBill->billId == $json["billId"]){
                    $found = 1;
                    $foundBill = $wsBill;
                    break;
                }
            }
        }
        if($json['action'] === 'join'){
            if($found == 0){
                $newBill = new wsBill($json["billId"], $from);
                $newBill->customersOrders[$from->resourceId] = array();
                array_push($this->wsBills, $newBill);
                echo 'New Bill Added'."\n";
            }else{
                $foundBill->customers->attach($from);
                echo 'Number of customers:'.count($foundBill->customers)."\n";
                echo 'Bill joined'."\n";
                if(!isset($foundBill->customersOrders[$from->resourceId])){
                    $foundBill->customersOrders[$from->resourceId] = array();
                }
                $foundBill->orders = array_values($foundBill->orders);
                $packet['action'] = 'wsorder.update';
                $packet['orders'] = $foundBill->orders;
                $from->send(json_encode($packet));
            }
        }

        if($json['action'] === 'pushOrder'){

            //for global order array
            $orderFound = 0;
            foreach($foundBill->orders as $order){
                if($json['orderId'] == $order){
                    $orderFound = 1;
                    break;
                }
            }
            if($orderFound == 0){
                array_push($foundBill->orders, $json['orderId']);
                echo 'Push Order ID:'.$json['orderId']."\n";
            }

            //for local order array
            $orderFound = 0;
            foreach($foundBill->customersOrders[$from->resourceId] as $order){
                if($json['orderId'] == $order){
                    $orderFound = 1;
                    break;
                }
            }
            if($orderFound == 0){
                array_push($foundBill->customersOrders[$from->resourceId], $json['orderId']);
                echo 'Push Order ID to '.$from->resourceId."\n";
            }

            $foundBill->orders = array_values($foundBill->orders);
            $packet['action'] = 'wsorder.update';
            $packet['orders'] = $foundBill->orders;
            foreach ($foundBill->customers as $customer) {
                if($customer !== $from){
                    $customer->send(json_encode($packet));
                }
            }
        }

        if($json['action'] === 'releaseOrder'){
            //for global order array
            $ii = 0;
            foreach($foundBill->orders as $order){
                if($json['orderId'] == $order){
                    unset($foundBill->orders[$ii]);
                    echo 'Release Order ID:'.$json['orderId']."\n";
                    break;
                }
                $ii++;
            }

            //for local order array
            $ii = 0;
            foreach($foundBill->customersOrders[$from->resourceId] as $order){
                if($json['orderId'] == $order){
                    unset($foundBill->customersOrders[$from->resourceId][$ii]);
                    echo 'Release Order ID from '.$from->resourceId."\n";
                    break;
                }
                $ii++;
            }

            $foundBill->orders = array_values($foundBill->orders);
            $packet['action'] = 'wsorder.update';
            $packet['orders'] = $foundBill->orders;
            foreach ($foundBill->customers as $customer) {
                if($customer !== $from){
                    $customer->send(json_encode($packet));
                }
            }
        }
        // $numRecv = count($this->clients) - 1;
        // echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        // , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        // foreach ($this->clients as $client) {
        //     // The sender is not the receiver, send to each client connected
        //     $client->send($msg);
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        foreach($this->wsBills as $wsBill){
            if($wsBill->customers->contains($conn)){
                foreach($wsBill->customersOrders[$conn->resourceId] as $localOrder){
                    $ii = 0;
                    foreach($wsBill->orders as $globalOrder){
                        if($localOrder == $globalOrder){
                            unset($wsBill->orders[$ii]);
                            echo 'Offline Release Order ID:'.$globalOrder."\n";
                            break;
                        }
                        $ii++;
                    }
                }
                $wsBill->customers->detach($conn);
                $wsBill->orders = array_values($wsBill->orders);
                $packet['action'] = 'wsorder.update';
                $packet['orders'] = $wsBill->orders;
                foreach ($wsBill->customers as $customer) {
                    $customer->send(json_encode($packet));
                }
                break;
            }
        }

        //finally detach the connection from global...
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
