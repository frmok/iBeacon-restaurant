<?php
    require realpath(dirname(__FILE__)).'/bootstrap/autoload.php';
    require_once realpath(dirname(__FILE__)).'/bootstrap/app.php';
    require realpath(dirname(__FILE__)).'/vendor/autoload.php';
	require realpath(dirname(__FILE__)).'/src/chat.php';
    $port = 9999;
    $pport = 5555;
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use MyApp\Chat;
	$loop   = React\EventLoop\Factory::create();
    $chat = new Chat();
    $context = new React\ZMQ\Context($loop);
    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:'.$pport); // Binding to 127.0.0.1 means the only client that can connect is itself
    $pull->on('message', array($chat, 'onQueue'));

    // Set up our WebSocket server for clients wanting real-time updates
    $webSock = new React\Socket\Server($loop);
    $webSock->listen($port, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
    $webServer = new Ratchet\Server\IoServer(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(
                    $chat
            )
        ),
        $webSock
    );

    $loop->run();
