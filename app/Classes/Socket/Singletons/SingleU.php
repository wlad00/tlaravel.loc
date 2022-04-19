<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;

class SingleU{

    private $arrUsers = [];
    private $user;
//    private $arrFriends;
    private $Msg;
    private $MapFriends = [];

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

    public function checkRemovedInFriends(){


        $emailsFriendsNew = [];

        foreach($this->Msg->emailsFriends as $friend_email){

            //1
            if(!isset($this->MapFriends[$friend_email])){

                array_push($emailsFriendsNew,$friend_email);
                continue;
            }

            $archiveEmails = $this->MapFriends[$friend_email];

            //2
            if(isset($archiveEmails[$this->Msg->email]))
                array_push($emailsFriendsNew,$friend_email);

        }

        $this->Msg->emailsFriends = $emailsFriendsNew;

    }


    public function updateMapFriends(){


        $this->MapFriends[$this->Msg->email] = $this->Msg->emailsFriends;

    }


    public function putUserData($conn)
    {

        $this->user = (object)[
            'enable' => true,
            'conn' => $conn,

            'name' => $this->Msg->name,
            'email' => $this->Msg->email,
            'rating' => $this->Msg->rating,

            'block' => $this->Msg->block
        ];

        $this->arrUsers[$this->Msg->email] = $this->user;
    }


    public function makeArrPersons(){

        $singleP = SingleP::getInstance();

        $singleP->setArrUsers($this->arrUsers);

        $this->arrPersons = $singleP->getArrPersons();

    }

    public function notifyThisUser(){

        $arrFriends = ChatService::makeArrFriends($this) ;

        //2 this User
        $U->this_user->conn->send( json_encode(
                ['arrPersons'=>$this->arrPersons,
                    'arrFriends'=>$arrFriends,
                    'type'=>'notify']
            )
        );
    }

        /**
     * @param $Msg
     * @throws \Exception
     */
    public static function sendMsg($Msg){

        $U = static::getInstance();

        if(is_numeric($Msg->email_to)){

            $Msg->TypeStep = '3_agree';
            $Msg->email_to = $Msg->email_from;

            echo "sleep 5 sec-------------\n";

            sleep(5);
        }
//        echo  json_encode($U->arrUsers)."\n";

        $conn = $U->arrUsers[$Msg->email_to]->conn;

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

        $U = static::getInstance();

        $email = ChatService::emailByConn($conn,$U->arrUsers);

        if(!$email){
            echo "-- disconnect VISITOR --\n" ;
            return;
        }

        $U->this_user = $U->arrUsers[$email];

//        $U->arrFriends = $user->arrFriends;

        static::notifyFriends();

        unset($U->arrUsers[$email]);




        echo "minusUser----- $email \n";



    }





    public static function notifyRemovedFriend($email_removed){

        $U = static::getInstance();

        $user = $U->arrUsers[$email_removed];

        $arrArch = $U->ArchiveFriends[$email_removed];


    }

    /**
     * @param $Msg
     * @throws \Exception
     */
    public static function updateArchiveFriends(&$Msg){

        $U = static::getInstance();

        $U->ArchiveFriends[$Msg->email] = ChatService::getEmailsFriends($Msg->arrFriends);

    }




    /**
     * @throws \Exception
     */
    /*public function updateFriends(){

        $U = static::getInstance();

        for($i=0;$i<sizeof($U->arrFriends);$i++){

            $friend = $U->arrFriends[$i];

            if(!isset($U->arrUsers[$friend->email])){

                $friend->enable = false;
                continue;
            }

            $user = $U->arrUsers[$friend->email];

            $friend->enable = true;
            $friend->conn = $user->conn;
            $friend->name = $user->name;
            $friend->rating = $user->rating;
            $friend->block = $user->block;
        }
    }*/

    /**
     * @param $Msg
     * @throws \Exception
     */
    /*public static function updateArchiveFriends($Msg){

        $U = static::getInstance();

        //1
        $U->ArchiveFriends[$Msg->user_email] = ChatService::getEmailsFriends($Msg->arrFriends);

        //2
        $user = $U->arrUsers[$Msg->user_email];

        $user->arrFriends = $Msg->arrFriends;
    }*/

    /**
     * @throws \Exception
     */
   /* public static function notifyThisUser(){

        $U = static::getInstance();


        ChatService::updateFriendsData($U->this_user->arrFriends,$U->arrUsers) ;

        //2 this User
        $U->this_user->conn->send( json_encode(
                ['arrPersons'=>$U->arrPersons,
                    'arrFriends'=>$U->this_user->arrFriends,
                    'type'=>'notify']
            )
        );


    }*/

    /**
     * @param $email
     * @throws \Exception
     */
    public static function notifyFriends(){

//        echo "notifyFriends()----1-----\n";

        $U = static::getInstance();

//        echo "notifyFriends----2------\n";

        //3 Friends
        foreach($U->this_user->arrFriends as $friend){

//            echo "11111------\n";

            if(!isset($U->arrUsers[$friend->email])) continue;

            $user = $U->arrUsers[$friend->email];
//            $emailsFriends = $U->mapFriends[$friend->email];



             ChatService::updateFriendsData($user->arrFriends,$U->arrUsers);

             if(isset($user->conn))

            $user->conn->send( json_encode(
                    ['arrPersons'=>$U->arrPersons,
                        'arrFriends'=>$user->arrFriends,
                        'type'=>'notify']
                )
            );
//
        }

    }




    /**
     *
     * @throws \Exception
     */
    public function removeIndex(){

        $size = sizeof($this->arrIndexes);

        if($size == 5) $this->addIndex();

        if($size <=5) return;

        $rand = random_int(0,$size-1);

//        unset($this->arrIndexes[$rand]);

        array_splice($this->arrIndexes, $rand, 1);
    }

    /**
     * @throws \Exception
     */
    public function addIndex(){

        $size = sizeof($this->arrIndexes);

        if($size==10) $this->removeIndex();

        if($size>=10) return;

        $size = sizeof(CONSTANT::ARR_BOTS)-1;

        $rand = random_int(0,$size);

        if(in_array($rand,$this->arrIndexes)) return;

        array_push($this->arrIndexes,$rand);
    }




    /**
     * @throws \Exception
     */
    private function makeArrIndexes(){

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
    }

    private function makeArrPersons(){

        $arrBots = CONSTANT::ARR_BOTS;
        $arrPersons = [];

        foreach($this->arrIndexes as $ind){

            array_push($arrPersons,$arrBots[$ind]);
        }

        $this->arrPersons = $arrPersons;

    }

    /*--------------------------------*/

    public function getArrPersons(){

        $this->makeArrPersons();

        return $this->arrPersons;
    }
    public function getArrIndexes(){

        return $this->arrIndexes;
    }



}