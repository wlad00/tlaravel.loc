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
use App\CONSTANT;
use App\Models\Person;
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

        echo 'server start----2-----';

       SingleP::readArrPersons();
    }

    public function onMessage(ConnectionInterface $conn, $msg) {

        $Msg = json_decode($msg);


        switch($Msg->type){

            case 'log':

//                echo "0.LOG --- "."\n";

                ChatService::saveLog($Msg); break;

            case 'remove_friend':

                echo "3.remove_friend --- ".$Msg->email."\n";


                $singleU = SingleU::getInstance($Msg);

                $singleU->removeFriend();

                $singleU->notifyRemovedFriend();


                break;

            case 'add_friends':

                echo "3.add_friends --- ".$Msg->email."\n";
//                echo "3.add_friends --- ".$msg."\n";

                $singleU = SingleU::getInstance($Msg);

                $singleU->addFriends();


//                SingleU::updateArchiveFriends($Msg);
//                SingleU::updateArchiveFriends($Msg);

                break;

            case 'interval': echo 'I-';
                $this->sendArrPersons(); break;

            case 'update_admin':

                echo "1.updateAdmin ----- $Msg->email \n";

                SingleU::setAdmin($conn);
                break;

            case 'update':

                echo "1.updateUser ----- $Msg->email \n";

                $singleU = SingleU::getInstance($Msg);

                /*------------------------*/
               /* if($Msg->email === 'admin@www.www'){



                    break;
                 }*/


                $singleU->putUserData($conn);

                $singleU->makeArrPersons();


                $singleU->notifyThisUser();

                $singleU->notifyFriends();


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

        echo "New connection {$conn->resourceId}\n";

    }


    /* Close */

    public function onClose(ConnectionInterface $conn) {

        $this->Connections->detach($conn);

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