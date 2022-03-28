<?php

namespace App\Classes\Socket;


class SingleU{

    private $arrUsers = [];
    private $this_user;
    private $arrFriends;

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

        $conn = $U->arrUsers[$Msg->email]->conn;

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

        $user = $U->arrUsers[$email];

        $U->arrFriends = $user->arrFriends;

        unset($U->arrUsers[$email]);

        static::notifyFriends();

    }

    /**
     * @param $Msg
     * @param $conn
     * @throws \Exception
     */
    public static function updateUser($Msg, $conn){

        $U = static::getInstance();

        ChatService::updateFriends($Msg->arrFriends,$U->arrUsers);

        $U->arrFriends = $Msg->arrFriends;

        $U->this_user = (object)[
            'conn'=>$conn,
            'arrFriends'=>$Msg->arrFriends,
            'name'=>$Msg->name,
            'email'=>$Msg->email,
            'rating'=>$Msg->rating,
            'block'=>$Msg->block,
            'enable'=>true
        ];

        $U->arrUsers[$Msg->email] = $U->this_user;

    }


    /**
     * @param $email
     * @throws \Exception
     */
    public static function notifyFriends(){

        echo 'notifyUsers-----------';

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

            $user = $U->arrUsers[$friend->email];

             ChatService::updateFriends($user->arrFriends,$U->arrUsers) ;

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