<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;

class SingleU{

    private $arrUsers = [];
    private $this_user;
    private $arrFriends;

    private $ArchiveFriends = [];

    private static $instance = null;
    /**
     * Persons constructor.
     * @throws \Exception
     */
    public function __construct()
    {

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

            sleep(5);
        }


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
            echo "-- disconnect VIS --\n" ;
            return;
        }

        $user = $U->arrUsers[$email];

        $U->arrFriends = $user->arrFriends;

        unset($U->arrUsers[$email]);

        static::notifyFriends();

    }

    /**
     * @param $Msg
     * @throws \Exception
     */
    public static function checkFriend($Msg){

        $U = static::getInstance();

        if(!isset($U->ArchiveFriends[$Msg->friend_email])) return;

        $friendEmails = $U->ArchiveFriends[$Msg->friend_email];

        if(in_array($Msg->user_email,$friendEmails)) return;


        $user = $U->arrUsers[$Msg->user_email];

        $user->conn->send( json_encode(
                ['friend_email'=>$Msg->friend_email,
                    'type'=>'remove_friend']
            )
        );

    } /**
     * @param $Msg
     * @throws \Exception
     */
    public static function checkToFriend($Msg){

        $U = static::getInstance();

//        if(!isset($U->ArchiveFriends[$Msg->friend_email])) return;

        $userEmails = $U->ArchiveFriends[$Msg->user_email];

        if(in_array($Msg->friend_email,$userEmails)) return;


        $friend = $U->arrUsers[$Msg->friend_email];

        $friend->conn->send( json_encode(
                ['friend_email'=>$Msg->user_email,
                    'type'=>'remove_friend']
            )
        );

    }

    /**
     * @param $Msg
     * @param $conn
     * @throws \Exception
     */
    public static function updateUser($Msg, $conn){

        $U = static::getInstance();

        echo "updateUser()---- \n";


        ChatService::updateFriends($Msg->arrFriends,$U->arrUsers);

        $U->arrFriends = $Msg->arrFriends;

        $U->this_user = (object)[
            'enable'=>true,
            'conn'=>$conn,

            'name'=>$Msg->name,
            'email'=>$Msg->email,
            'rating'=>$Msg->rating,

            'arrFriends'=>$Msg->arrFriends,
            'block'=>$Msg->block
        ];

        $U->arrUsers[$Msg->email] = $U->this_user;


        $U->ArchiveFriends[$Msg->email] = ChatService::getEmailsFriends($Msg->arrFriends);


       /* echo json_encode($U->this_user,JSON_UNESCAPED_UNICODE);
        echo "\n";*/
    }

    /**
     * @param $Msg
     * @throws \Exception
     */
    public static function updateArchiveFriends($Msg){

        $U = static::getInstance();

        $U->ArchiveFriends[$Msg->user_email] = ChatService::getEmailsFriends($Msg->arrFriends);

    }


    /**
     * @param $email
     * @throws \Exception
     */
    public static function notifyFriends(){

        echo "notifyFriends()---------\n";

        $U = static::getInstance();

        //1 Persons
        $singleP = SingleP::getInstance();

        $singleP->setArrUsers($U->arrUsers);

        $arrPersons = $singleP->getArrPersons();

        //2 this User
        $U->this_user->conn->send( json_encode(
                ['arrPersons'=>$arrPersons,
                    'arrFriends'=>$U->this_user->arrFriends,
                    'type'=>'notify']
            )
        );
        //3 Friends
        foreach($U->arrFriends as $friend){

            if(!isset($U->arrUsers[$friend->email])) continue;

            $user = $U->arrUsers[$friend->email];

             ChatService::updateFriends($user->arrFriends,$U->arrUsers) ;

             if(isset($friend->conn))

            $friend->conn->send( json_encode(
                    ['arrPersons'=>$arrPersons,
                        'arrFriends'=>$user->arrFriends,
                        'type'=>'notify']
                )
            );
//
        }

    }

    /**
     * @return SingleU|null
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new static();
        }

        return self::$instance;
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