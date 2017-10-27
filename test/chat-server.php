<?php
use Ratchet\Server\IoServer;
use MyApp\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpSever(
            new WsServer(
                new Chat()
            )
        ),
        8080
    );

    $server->run();