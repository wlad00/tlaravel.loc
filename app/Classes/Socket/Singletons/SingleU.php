<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;
use App\Models\Bot;

class SingleU{

    public $MapUsers = [];

    public $admin = null;

    public $MapAdmin = [];

    public $arrPersons =[];

    public $user;


    private static $instance = null;

    /**
     * SingleU constructor.
     * @throws \Exception
     */
    public function __construct()
    {

//        $this->makeMapAdmin();
    }

    /**
     * @throws \Exception
     */
    /*public function makeMapAdmin(){
        $singleP = SingleP::getInstance();


        foreach($singleP->arrBots as $bot){

            $this->MapAdmin[$bot['email']]=$bot;
        }

    }*/
    /**
     * @param $Msg
     * @return SingleU|null
     * @throws \Exception
     */
    public static function getInstance(/*$Msg*/)
    {
        if (self::$instance == null)
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function makeArrPersons(){

        $arrPersons = [];

        foreach($this->MapUsers as $email=>$user){

            if(!$user->block)
                array_push($arrPersons,$user);
        }

        $this->arrPersons = $arrPersons;
    }

    /**
     * @param $conn
     * @param $Msg
     * @throws \Exception
     */
    public function putUserData($conn, $Msg){

//        $selfU = self::getInstance();


        $this->user = (object)[

            'enable' => true,
            'conn' => $conn,
            'arrFriends' => $Msg->arrFriends,

            'name' => $Msg->name,
            'email' => $Msg->email,

            'rating' => $Msg->rating,
            'block' => $Msg->block
        ];

        $this->MapUsers[$Msg->email] = $this->user;
    }

    /* 1_ UPDATE */

    public function setAdmin($conn){

        $this->admin = (object)[
            'conn'=>$conn
        ];
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function putBotsToArrUsers(){

        $objConfig = ChatService::readStorage('config.txt');

        $num_bots = $objConfig->num_bots;

        $AllBots = Bot::where('id','<=',$num_bots)->get(['email','name','rating','arr_friends','block'])->toArray();


//        ChatService::echo_arr($AllBots,'/AllBots----');

        for($i=0;$i<$num_bots;$i++){

            $bot = $AllBots[$i];
            $bot['arrFriends']=json_decode($bot['arr_friends']);
            unset($bot['arr_friends']);

            $this->MapUsers[$bot['email']]=(object)$bot;
        }
    }



    public function responseAdmin(){

        $data = ['MapUsers'=>$this->MapUsers,
            'type'=>'response_admin'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->admin->conn->send( $json);
    }



    /**
     * @throws \Exception
     */
    /*public function makeArrPersons(){

//        echo "000\n";

//        $singleU = self::getInstance();
//
//        $singleP = SingleP::getInstance();


//        echo "1\n";

        $singleP->setArrUsers($singleU->MapUsers);

//        echo "2\n";

        $singleU->arrPersons = $singleP->getArrPersons();

//        ChatService::echo_arr($singleU->arrPersons->toArray(),'arrPersons');
    }*/

    /**
     * @param $Msg
     * @throws \Exception
     */
    public static function removeFriend($Msg){

        $singleU = self::getInstance();

        $user = $singleU->MapUsers[$Msg->email];

        $user->arrFriends = array_filter($user->arrFriends, function($friend) use($Msg) {
            return $friend->email != $Msg->email_removed;
        });
    }

    /**
     * @param $Msg
     * @throws \Exception
     */
    public static function addFriends($Msg)
    {
        $singleU = self::getInstance();

        /* get Friend */

        $friendOrigin = $singleU->MapUsers[$Msg->friend_email];
        $newFriend = ChatService::getFriendForArr($friendOrigin);

        /* get User */


        $userOrigin = $singleU->MapUsers[$Msg->email];
        $newUser = ChatService::getFriendForArr($userOrigin);


        array_push($userOrigin->arrFriends,$newFriend);
        array_push($friendOrigin->arrFriends,$newUser);
    }




    /*public function removeAllBots(){

        foreach ( $this->MapUsers as $email => $value ) {
            if ( is_numeric($email) ) {
                unset($this->MapUsers[$email]);
            }
        }
    }*/

    /**
     * @throws \Exception
     */
   /* public function makeArrPersons(){


        $singleP = SingleP::getInstance();

        $singleP->setArrUsers($this->MapUsers);

        $this->arrPersons = $singleP->getArrPersons();
    }*/

    /*public function notifyBots($arrBotMails){


        if(!$this->admin)
            return;

        $arrBots = [];

        foreach($arrBotMails as $bot_email){

            array_push($arrBots,$this->MapUsers[$bot_email]);
        }

        $data = ['arrPersons'=>$this->arrPersons,
            'arrBots'=>$arrBots,
            'type'=>'notify'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->admin->conn->send( $json);

    }*/



}