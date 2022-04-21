<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;

class SingleU{

    private $MapUsers = [];
    private $user;
//    private $arrFriends;
    private $Msg;
//    private $MapFriends = [];

    private $arrPersons;

//    private $ArchiveFriends = [];

    private static $instance = null;

    /**
     * @param $Msg
     * @return SingleU|null
     * @throws \Exception
     */
    public static function getInstance($Msg)
    {
        if (self::$instance == null)
        {
            self::$instance = new static();
        }

        self::$instance->Msg = $Msg;

        return self::$instance;
    }

    /* 1_ UPDATE */

    public function putUserData($conn)
    {

        $this->user = (object)[

            'enable' => true,
            'conn' => $conn,
            'arrFriends' => $this->Msg->arrFriends,

            'name' => $this->Msg->name,
            'email' => $this->Msg->email,

            'rating' => $this->Msg->rating,
            'block' => $this->Msg->block
        ];

        $this->MapUsers[$this->Msg->email] = $this->user;
    }

    public function makeArrPersons(){

        $singleP = SingleP::getInstance();

        $singleP->setArrUsers($this->MapUsers);

        $this->arrPersons = $singleP->getArrPersons();

    }



    public function notifyThisUser(){

        $this->checkArrFriends();

        $data = ['arrPersons'=>$this->arrPersons,
            'arrFriends'=>$this->user->arrFriends,
            'type'=>'notify'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->user->conn->send( $json);
    }



    public function notifyFriends(){

        //3 Friends
        foreach($this->user->arrFriends as $friend){

            if(!isset($this->MapUsers[$friend->email])) continue;

            /* get friend from MapUsers*/

            $this->user = $this->MapUsers[$friend->email];

            $this->notifyThisUser();

            /*$this->checkArrFriends($user);

            $data = ['arrPersons'=>$this->arrPersons,
                'arrFriends'=>$this->user->arrFriends,
                'type'=>'notify'];

            $json = json_encode($data,JSON_UNESCAPED_UNICODE);



            //2 this User

            $user->conn->send( $json);*/

        }

    }


    /* 1_ PRIVATE UPDATE */

    private function checkArrFriends(){

//        $emailsFriends = [];
        $arrFriendsNew = [];
        $user_email = $this->user->email;

        //0
        foreach($this->user->arrFriends as $friend){

            //1
            if(is_numeric($friend->email)){

                $friend->enable = true;
                array_push($arrFriendsNew,$friend);
                continue;
            }
            //2
            if(!isset($this->MapUsers[$friend->email])){

                $friend->enable = false;
                array_push($arrFriendsNew,$friend);
                continue;
            }
            //3
            $friendOrigin =  $this->MapUsers[$friend->email];

            //4
            $in_array = array_filter($friendOrigin->arrFriends, function($friend)use ($user_email) {
                return $friend->email == $user_email;
            });

            //5
            if(sizeof($in_array)>0){

                ChatService::appendStorage('notify/notify_friends.txt','in_array>0');
                $friend->name = $friendOrigin->name;
                $friend->rating = $friendOrigin->rating;
                $friend->block = $friendOrigin->block;
                $friend->enable = true;
                array_push($arrFriendsNew,$friend);
            }
        }

        ChatService::appendStorage('notify/notify_friends.txt',
            $this->user->email.' <= '.
            json_encode($arrFriendsNew,JSON_UNESCAPED_UNICODE));

        //6
        $this->user->arrFriends = $arrFriendsNew;

        //7
        $this->MapUsers[$this->user->email] = $this->user;
    }


        /**
     * @param $Msg
     * @throws \Exception
     */
    public static function sendMsg($Msg){

        $U = static::getInstance($Msg);

        if(is_numeric($Msg->email_to)){


            if($Msg->TypeStep == 'text'){

                $Msg->email_bot = $Msg->email_to;
                $Msg->email_to = 'wladsliw@list.ru';
            }


            if($Msg->TypeStep == '1_invite'){

                $Msg->TypeStep = '3_agree';
                $email_bot = $Msg->email_to;
                $Msg->email_to = $Msg->email_from;
                $Msg->email_from = $email_bot;

                echo "sleep 5 sec-------------\n";

                sleep(5);
            }

        }
//        echo  json_encode($U->arrUsers)."\n";

        if(!isset($U->MapUsers[$Msg->email_to]))
                                            return;

        $conn = $U->MapUsers[$Msg->email_to]->conn;

        $conn->send( json_encode(
                $Msg
            )
        );
    }


    /**
     * @param $conn
     * @throws \Exception
     */
    public static function minusUser($conn){

        $U = static::getInstance(null);

        $email = ChatService::emailByConn($conn,$U->MapUsers);

        if(!$email){
            echo "-- disconnect VISITOR --\n" ;
            return;
        }
        $U->user = $U->MapUsers[$email];

//        echo 'minusUser ->'."\n";

//        $U->user->arrFriends = $U->MapUsers[$email]->arrFriends;

        unset($U->MapUsers[$email]);

//        echo json_encode($U->user,JSON_UNESCAPED_UNICODE)."\n";

        $U->notifyFriends();
    }



    public function removeFriend(){

        $user = $this->MapUsers[$this->Msg->email];

        $user->arrFriends = array_filter($user->arrFriends, function($friend) {
            return $friend->email != $this->Msg->email_removed;
        });
    }

    public function notifyRemovedFriend(){

        if(!isset($this->MapUsers[$this->Msg->email_removed]))
                                                        return;

        $this->user = $this->MapUsers[$this->Msg->email_removed];

        $this->notifyThisUser();
    }

    public function addFriends(){

//        echo $this->Msg."\n";

        $user = $this->MapUsers[$this->Msg->email];
//        echo "1\n";
        $friend = $this->MapUsers[$this->Msg->friend_email];
//        echo "2\n";

        array_push($user->arrFriends,$friend);
//        echo "3\n";
        array_push($friend->arrFriends,$user);
//        echo "4\n";

    }


    /**
     * @param $Msg
     * @throws \Exception
     */
    /*public static function updateArchiveFriends(&$Msg){

        $U = static::getInstance();

        $U->ArchiveFriends[$Msg->email] = ChatService::getEmailsFriends($Msg->arrFriends);

    }*/




    /**
     *
     * @throws \Exception
     */
    /*public function removeIndex(){

        $size = sizeof($this->arrIndexes);

        if($size == 5) $this->addIndex();

        if($size <=5) return;

        $rand = random_int(0,$size-1);

//        unset($this->arrIndexes[$rand]);

        array_splice($this->arrIndexes, $rand, 1);
    }*/

    /**
     * @throws \Exception
     */
    /*public function addIndex(){

        $size = sizeof($this->arrIndexes);

        if($size==10) $this->removeIndex();

        if($size>=10) return;

        $size = sizeof(CONSTANT::ARR_BOTS)-1;

        $rand = random_int(0,$size);

        if(in_array($rand,$this->arrIndexes)) return;

        array_push($this->arrIndexes,$rand);
    }*/




    /**
     * @throws \Exception
     */
    /*private function makeArrIndexes(){

        $arrBots = CONSTANT::ARR_BOTS;
        $arrIndexes = [];

        for($i=0; $i<5; $i++){

            $size = sizeof($arrBots)-1;

            $rand = random_int(0, $size);

            if(in_array($rand,$arrIndexes))
                $i--;
            else
                array_push($arrIndexes,$rand);
        }

        $this->arrIndexes = $arrIndexes;
    }*/

    /*private function makeArrPersons(){

        $arrBots = CONSTANT::ARR_BOTS;
        $arrPersons = [];

        foreach($this->arrIndexes as $ind){

            array_push($arrPersons,$arrBots[$ind]);
        }

        $this->arrPersons = $arrPersons;

    }*/

    /*--------------------------------*/

    /*public function getArrPersons(){

        $this->makeArrPersons();

        return $this->arrPersons;
    }
    public function getArrIndexes(){

        return $this->arrIndexes;
    }*/



}