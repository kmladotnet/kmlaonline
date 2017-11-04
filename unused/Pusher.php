<?php
namespace MyApp;

require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->subscribedTopics[$topic->getId()] = $topic;
        echo $topic->getId() . "\n";
        //print_r($topic);
        //print_r($subscribedTopics);
    }

    public function onBlogEntry($entry) {
        echo "tcp connection \n";
        $entryData = json_decode($entry, true);

        if(!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];
        $topic->broadcast($entryData);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "New connection!\n";
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Connection has disconnected\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params){
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible){
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occured: {$e->getMessage()}\n";

        $conn->close();
    }
}