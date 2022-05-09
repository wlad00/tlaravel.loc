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
use App\Classes\Socket\Workers\WorkerAdd;
use App\Classes\Socket\Workers\WorkerRemove;
use App\Classes\Socket\Workers\WorkerSend;
use App\Classes\Socket\Workers\WorkerUpdate;
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

//       SingleP::readArrPersons();
    }

    /**
     * @param ConnectionInterface $conn
     * @param string $msg
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $conn, $msg) {

        $Msg = json_decode($msg);


        switch($Msg->type){

            case 'log':

//                echo "0.LOG --- "."\n";

                ChatService::saveLog($Msg); break;

            case 'remove_friend':

                echo "3.remove_friend --- ".$Msg->email."\n";

//                $worker = new WorkerRemove($Msg);

//                $worker->removeFriend();

                SingleU::removeFriend($Msg);

                /*-----------------------------*/

                $worker = new WorkerUpdate($Msg);

                $worker->notifyRemovedFriend();

                $worker->setBotEmails([$Msg->email_removed]);

                $worker->notifyBots();


                break;

            case 'add_friends':

                echo "3.add_friends --- ".$Msg->email."\n";

//                $worker = new WorkerAdd();

                SingleU::addFriends($Msg);

                /*--------------------------------*/

                $worker = new WorkerUpdate($Msg);

                $worker->setBotEmails([$Msg->friend_email,$Msg->email]);

                $worker->notifyBots();


                break;

            case 'interval': echo 'I-';
                $this->sendArrPersons(); break;

            case 'login_admin':

                echo "1.login_admin --11--- $Msg->email \n";

                $singleU = SingleU::getInstance();

//                echo "1\n";
                $singleU->setAdmin($conn);
//                echo "2\n";
                $singleU->putBotsToArrUsers();
//                echo "3\n";
                $singleU->responseAdmin();
//                echo "4\n";

                break;

            case 'update':

                echo "1.update ----- $Msg->email \n";


                $singleU = SingleU::getInstance();

                $singleU->putUserData($conn,$Msg);

                $singleU->makeArrPersons();

                /*--------------------------------*/


                $worker = new WorkerUpdate($Msg);

                $worker->setThisUser();
//                echo "2\n";
                $worker->notifyThisUser();
//                echo "3\n";
                $worker->notifyFriends();
//                echo "4\n";
                $worker->notifyBots();

                break;

            case 'send':
                $worker = new WorkerSend($Msg);

//                $worker->sendMsg();

                $worker->sendToUser();

                $worker->sendToBot();

                $worker->returnAgreeBot();


//                $worker->sendInviteGame();
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

        $worker = new WorkerUpdate(null);

        $worker->minusUser($conn);

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