<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.02.2022
 * Time: 12:53
 */

namespace App\Classes\Socket;


use App\Classes\Socket\Singletons\SingleP;
use App\Classes\Socket\Singletons\SingleU;
use App\Classes\Socket\Base\BaseSocket;
use Ratchet\ConnectionInterface;

class ChatSocket extends BaseSocket
{
    protected $Connections;
    protected $arrUserSocket;
    protected $persons;
    protected $startInterval = false;


    /**
     * ChatSocket constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->Connections = new \SplObjectStorage;

    }

    public function onMessage(ConnectionInterface $conn, $msg) {

        $Msg = json_decode($msg);

//        echo $msg;
//        echo "\n";
       /* echo gettype($Msg);
        echo "\n";*/


        switch($Msg->type){

            case 'log': echo 'LOG-';

                ChatService::saveLog($Msg); break;

            /*case 'removeFriend':

                SingleU::updateArchiveFriends($Msg);
                SingleU::checkFriend($Msg); break;*/

            case 'check_friend':

                SingleU::updateArchiveFriends($Msg);
                SingleU::checkFriend($Msg);
                SingleU::checkToFriend($Msg); break;


            case 'interval': echo 'I-';
                $this->sendArrPersons(); break;

            case 'update':

                SingleU::updateUser($Msg,$conn);
                SingleU::notifyFriends(); break;

            case 'send':
                SingleU::sendMsg($Msg);

        }

    }

    /**
     * @throws \Exception
     */
    private function sendArrPersons(){

        $singleP = SingleP::getInstance();

        $rand2 = random_int(0,1);

        if($rand2>0) $singleP->addIndex();
        else
            $singleP->removeIndex();

        $arrPersons = $singleP->getArrPersons();
        $arrIndexes = $singleP->getArrIndexes();

        foreach($this->Connections as $conn){

            $conn->send(json_encode(
                ['rand2'=>$rand2,
                    'connId' => $conn->resourceId,
                    'arrPersons' => $arrPersons,
                    'arrIndexes' => $arrIndexes]
            ));
        }
    }


    public function onOpen(ConnectionInterface $conn) {

        $this->Connections->attach($conn);

        echo "New connection 2! ({$conn->resourceId})\n";

        echo $conn->remoteAddress."\n";

        $singleP = SingleP::getInstance();

        $arrPersons = $singleP->getArrPersons();

        $conn->send(json_encode(
            ['arrPersons' => $arrPersons,
                'arrFriends'=>[],
                'type'=>'notify'
                ]
        ));

    }


    /* Close */

    public function onClose(ConnectionInterface $conn) {

        $this->Connections->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";

        SingleU::minusUser($conn);

    }

    /* Error */

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


}