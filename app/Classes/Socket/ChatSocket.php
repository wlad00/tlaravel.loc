<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.02.2022
 * Time: 12:53
 */

namespace App\Classes\Socket;


use App\Classes\Socket\Base\BaseSocket;
use Ratchet\ConnectionInterface;

class ChatSocket extends BaseSocket
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";

        echo $conn->remoteAddress."\n";
//        var_dump($conn);
    }

    public function onMessage(ConnectionInterface $conn, $msg) {
        /*$numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }*/

        $jsonMsg = json_decode($msg);

        if ($jsonMsg->type == "login") {

            $onlineUsers = [];
            $onlineUsers['type'] = "onlineUsers";

            /*--------------------------------*/

            $this->activeUsers[$conn->resourceId] = $jsonMsg->name;

            /*------------------------------------*/

            $this->updateSocketId($jsonMsg->name,$conn->resourceId);

            $onlineUsers['onlineUsers'] = $this->activeUsers;
            $this->sendMessageToAll(json_encode($onlineUsers));

        } elseif ($jsonMsg->type == "message") {
            $this->sendMessageToUser($conn, $jsonMsg);
        }


    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


}