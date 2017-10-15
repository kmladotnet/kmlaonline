<?php
namespace MyApp;
use Ratchet\MesseageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MesseageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}