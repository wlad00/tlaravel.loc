<?php

namespace App\Classes\Socket\Workers;


use App\Classes\Socket\ChatService;
use App\Classes\Socket\Singletons\SingleP;
use App\Classes\Socket\Singletons\SingleU;

class WorkerUpdate
{

    private $Msg;

    private $singleU;

    /* mutable */

    private $user;

    private $arrBotMails = [];

    /**
     * WorkerUpdate constructor.
     * @param $Msg
     * @throws \Exception
     */
    public function __construct($Msg)
    {
        $this->Msg = $Msg;
        $this->singleU = SingleU::getInstance();

    }

    /*public function putUserData($conn){

        $this->user = (object)[

            'enable' => true,
            'conn' => $conn,
            'arrFriends' => $this->Msg->arrFriends,

            'name' => $this->Msg->name,
            'email' => $this->Msg->email,

            'rating' => $this->Msg->rating,
            'block' => $this->Msg->block
        ];

        $this->singleU->MapUsers[$this->Msg->email] = $this->user;
    }*/


    /**
     * @throws \Exception
     */
    /*public function makeArrPersons(){


        $singleP = SingleP::getInstance();

        $singleP->setArrUsers($this->singleU->MapUsers);

        $this->arrPersons = $singleP->getArrPersons();
    }*/

    /*-----------------------------------*/


    public function setBotEmails($arrEmails){

        $this->arrBotMails = array_filter($arrEmails,function($email){
            return is_numeric($email);
        });
    }

    public function setThisUser(){

        $this->user = $this->singleU->user;
    }

    public function notifyThisUser(){

        echo "/notifyThisUser".$this->user->email."\n";
        $this->checkArrFriends();

        echo "12\n";

        if(is_numeric($this->user->email)) {

            array_push($this->arrBotMails,$this->user->email);
            return;
        }
//        echo "1\n";
        echo "13\n";

        $data = [
//            'mapPersons'=> SingleP::getMapPersons(),
            'arrPersons'=>$this->singleU->arrPersons,
            'arrFriends'=>$this->user->arrFriends,
            'type'=>'notify'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->user->conn->send( $json);

    }

    /**
     *
     */
    public function notifyFriends(){

//        echo json_encode($this->MapUsers)."\n";

        echo "/notifyFriends-----";


        foreach($this->user->arrFriends as $friend){

            if(!isset($this->singleU->MapUsers[$friend->email]))
                continue;

//            echo 'update friend: '.$friend->email."\n";

            $this->user = $this->singleU->MapUsers[$friend->email];

            $this->notifyThisUser();

        }
    }



    public function notifyAll(){


        echo "/notifyAll----\n";

        $this->notifyAdmin();

//        ChatService::echo_arr($this->singleU->MapUsers,'/MapUsers--------');


        foreach($this->singleU->MapUsers as $email=>$friend){

            if(is_numeric($email))
                                    continue;

            $this->user = $this->singleU->MapUsers[$email];

//            echo 'user to notify -> '.$this->user->email."\n";

            $this->notifyThisUser();

        }
    }
    public function notifyAdmin(){

        if(!isset($this->singleU->admin))
            return;

        $ArrBots = [];

        foreach($this->singleU->MapUsers as $user){

            $clone_user = clone $user;
            unset($clone_user->conn);
            array_push($ArrBots,$clone_user);
        }


        $data = [

            'ArrBots'=>$ArrBots,
            'type'=>'response_admin'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->admin->conn->send( $json);
    }



    public function notifyBots(){


        if(!$this->singleU->admin)
                                return;

        $arrBots = [];

        foreach($this->arrBotMails as $bot_email){

            array_push($arrBots,$this->singleU->MapUsers[$bot_email]);
        }

        $data = [
            'arrBots'=>$arrBots,
            'type'=>'notify_admin'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->singleU->admin->conn->send( $json);

    }

    public function notifyRemovedFriend(){

        if(!isset($this->singleU->MapUsers[$this->Msg->email_removed]))
            return;

        $this->user = $this->singleU->MapUsers[$this->Msg->email_removed];

        $this->notifyThisUser();
    }




    /**
     * @param $conn
     * @throws \Exception
     */
    public function minusUser($conn){

        $singleU = SingleU::getInstance();


        /* Exit Admin */


        if($singleU->admin->conn->resourceId === $conn->resourceId){

            $singleU->admin = null;

            $singleU->removeAllBots();

            $singleU->makeArrPersons();

            $this->notifyAll();

            return;
        }

        /* Exit Visitor */


        $email = ChatService::emailByConn($conn,$singleU->MapUsers);

        if(!$email){
            echo "-- disconnect VISITOR --\n" ;
            return;
        }

        /* Remove User */


        $this->user = $singleU->MapUsers[$email];

        unset($singleU->MapUsers[$email]);

        $singleU->makeArrPersons();


        /* Notify */


        if($this->user->block)
            $this->notifyFriends();
        else
            $this->notifyAll();
    }

    /* 1_ PRIVATE UPDATE */


    private function checkArrFriends(){

        $arrFriendsNew = [];
        $user_email = $this->user->email;

        //0
        foreach($this->user->arrFriends as $friend){

            //1
            /*if(is_numeric($friend->email)){

                $friend->enable = true;
                array_push($arrFriendsNew,$friend);
                continue;
            }*/
            //2
            if(!isset($this->MapUsers[$friend->email])){

                $friend->enable = false;
                array_push($arrFriendsNew,$friend);
                continue;
            }

            //3
            $friendOrigin =  $this->singleU->MapUsers[$friend->email];

//            echo $user_email.' Friend: '.$friendOrigin->email." arrFriends -> \n";
//            echo json_encode($friendOrigin->arrFriends)."\n";
            //4
            $in_array = array_filter($friendOrigin->arrFriends, function($friend)use ($user_email) {
                return $friend->email == $user_email;
            });

            //5
            if(sizeof($in_array)>0){

                echo $user_email.' newFriend: '.$friendOrigin->email."\n";

                $newFriend = ChatService::getFriendForArr($friendOrigin);

                array_push($arrFriendsNew,$newFriend);
            }
        }

//        echo $user_email." arrFriendsNew=> \n";
//        echo var_dump($arrFriendsNew);

        //6
        $this->user->arrFriends = $arrFriendsNew;

        //7
        $this->singleU->MapUsers[$this->user->email] = $this->user;
    }


}
