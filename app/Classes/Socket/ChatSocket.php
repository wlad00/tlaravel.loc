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

//        echo  'Msg -> '.$Msg->type."\n";
//        echo "\n";
       /* echo gettype($Msg);
        echo "\n";*/
//       $i = 4/0;


        switch($Msg->type){

            case 'log':

                echo "0.LOG --- ".$msg."\n";

                ChatService::saveLog($Msg); break;

            /*case 'removeFriend':

                SingleU::updateArchiveFriends($Msg);
                SingleU::checkFriend($Msg); break;*/
            case 'remove_friend':

                echo "3.adding_friends --- ".$Msg->email."\n";

                SingleU::updateArchiveFriends($Msg);

                SingleU::notifyRemovedFriend($Msg->email_removed);


                break;

            case 'adding_friends':

                echo "3.adding_friends --- ".$Msg->email."\n";

                SingleU::updateArchiveFriends($Msg);
//                SingleU::updateArchiveFriends($Msg);

                break;


            /* case 'check_friend':

                            echo "2.check_friend --- ".$Msg->friend_email."\n";

                            SingleU::updateArchiveFriends($Msg);


                            SingleU::checkFriendToUser($Msg);


                            SingleU::checkToFriend($Msg);

                            break;*/


            case 'interval': echo 'I-';
                $this->sendArrPersons(); break;

            case 'update':

                echo "1.updateUser ----- $Msg->email \n";

                $singleU = SingleU::getInstance($Msg);

                $singleU->checkRemovedInFriends();

                $singleU->updateMapFriends();

                $singleU->putUserData($conn);

                $singleU->makeArrPersons();

                $singleU->notifyThisUser();


                SingleU::notifyThisUser();
                echo "4\n";

                SingleU::notifyFriends();
                echo "5\n";

                break;

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

        /*$singleP = SingleP::getInstance();

        $arrPersons = $singleP->getArrPersons();

        $conn->send(json_encode(
            ['arrPersons' => $arrPersons,
                'arrFriends'=>[],
                'type'=>'notify'
                ]
        ));*/

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

        $conn->send( json_encode(
            ['type'=>'error', 'error'=>$e->getMessage()

        ]
        ));


        $conn->close();
    }


}