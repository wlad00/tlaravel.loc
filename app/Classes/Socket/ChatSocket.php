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
    protected $arrUserSocket;
    protected $persons;
    protected $startInterval = false;


    /**
     * ChatSocket constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;

//        $this->startInterval();
    }

    public function onMessage(ConnectionInterface $conn, $msg) {

        $Msg = json_decode($msg);


        switch($Msg->type){

            case 'interval': echo 'I-';
                $this->sendArrPersons(); break;

            case 'login': echo 'login '.$Msg->email;
//            echo '   '.json_encode($Msg->arrFriends,JSON_UNESCAPED_UNICODE);
            Actives::addToArrActives($Msg,$conn);
//            Actives::notifyUsers($Msg->email);
            break;

            case 'invite': break;
            case 'chancel_invite': break;
        }
//
//        if($Msg->type == 'interval'){
//            echo 'int-------';
//            $this->sendArrPersons();
//            return;
//        }

    }

    /**
     * @throws \Exception
     */
    private function sendArrPersons(){

        $persons = Persons::getInstance();

        $rand2 = random_int(0,1);

        if($rand2>0) $persons->addIndex();
        else
            $persons->removeIndex();

        $arrPersons = $persons->getArrPersons();
        $arrIndexes = $persons->getArrIndexes();

        foreach($this->clients as $client){

            $client->send(json_encode(
                ['rand2'=>$rand2,
                    'connId' => $client->resourceId,
                    'arrPersons' => $arrPersons,
                    'arrIndexes' => $arrIndexes]
            ));
        }
    }


    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection 2! ({$conn->resourceId})\n";

        echo $conn->remoteAddress."\n";
//        var_dump($conn);

        /*$persons = new Persons();

        $conn->send(json_encode(
            ['connId' => $conn->resourceId,
            'arrPersons' => 'arrPersons']
        ));*/


        /*if(!$this->startInterval) {
            $this->startInterval= true;
            $this->startInterval();
        }*/
    }
    public function updateSocketId($user,$id){

        $data = $this->database->select('socket_id', [
            'user'
        ], [
            'user' => $user
        ]);
        if(empty($data)){
            // insert
            //echo 'Insert';
            $this->database->insert('socket_id', [
                'user' => $user,
                'socket_id' => $id
            ]);
        }else{
            // update
            //echo 'Update';
            $data = $this->database->update('socket_id', [
                'socket_id' => $id
            ], [
                'user' => $user
            ]);
        }
    }

    public function onMessage1(ConnectionInterface $conn, $msg) {

        $Msg = json_decode($msg);

        if($Msg->type == 'interval'){
            echo 'int-------';
            return;
        }








        $W = new WorkerSocket($conn, $msg);


        $W->login();



        return;

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
    public function sendMessageToUser($conn, $msg){
        $to = $msg->data->to;
        $data = $this->database->select('socket_id', [
            'socket_id'
        ], [
            'user' => $to
        ]);

        $toSocketId = $data[0]['socket_id'];

        foreach ($this->clients as $client) {
            if ($client->resourceId == $toSocketId) {
                $client->send(json_encode(['type' => 'message','data' => $msg->data]));
            }
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