<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyWebsocket\Websocket;

    require dirname(dirname(__DIR__)).'/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Websocket()
            )
        ),
        1337
    );

    $server->run();