<?php

    
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    //use MyApp\WebSocket;


    //require 'vendor/autoload.php';
    //require_once PATH_TDC . 'websocket.class.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebSocket()
            )
        ),
        2715, '0.0.0.0'
    );

    $server->run();