<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;
use App\Models\Bot;

class SingleU{

    private $MapUsers = [];
    private $MapAdmin = [];
    private $admin = null;

    private $user;
    private $Msg;
//    private $type

    private $arrPersons;


    private static $instance = null;

    /**
     * SingleU constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->makeMapAdmin();
    }

    /**
     * @throws \Exception
     */
    public function makeMapAdmin(){
        $singleP = SingleP::getInstance();

        foreach($singleP->arrBots as $bot){

            $bot->arrFriends = json_decode($bot->arr_friends);

            unset($bot->arr_friends);

            $this->MapAdmin[$bot->email]=$bot;
        }
    }
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

    public function setAdmin($conn){


        $this->admin = (object)[
            'conn'=>$conn
        ];

    }
    public function notifyAdmin(){

        $data = ['MapAdmin'=>$this->MapAdmin,
            'type'=>'update_admin'];

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->admin->conn->send( $json);
    }

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

    /**
     * @throws \Exception
     */
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


        foreach($this->user->arrFriends as $friend){

            if(!isset($this->MapUsers[$friend->email]))
                                                continue;

            $this->user = $this->MapUsers[$friend->email];

            $this->notifyThisUser();

        }

    }


    /* 1_ PRIVATE UPDATE */

    private function checkArrFriends(){

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

                $newFriend = $this->getFriendForArr($friendOrigin);

               /* $friend->name = $friendOrigin->name;
                $friend->rating = $friendOrigin->rating;
                $friend->block = $friendOrigin->block;
                $friend->enable = true;
                array_push($arrFriendsNew,$friend);*/

                array_push($arrFriendsNew,$newFriend);
            }
        }

        //6
        $this->user->arrFriends = $arrFriendsNew;

        //7
        $this->MapUsers[$this->user->email] = $this->user;
    }
    private function getFriendForArr($friendOrigin){

        $newFriend = (object)[];
        $newFriend->name = $friendOrigin->name;
        $newFriend->rating = $friendOrigin->rating;
        $newFriend->block = $friendOrigin->block;
        $newFriend->enable = true;

        return $newFriend;
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
                $Msg->email_to = 'admin@www.www';
            }


            if($Msg->TypeStep == '1_invite'){

                $Msg->TypeStep = '3_agree';
                $email_bot = $Msg->email_to;
                $Msg->email_to = $Msg->email_from;
                $Msg->email_from = $email_bot;

                if($Msg->InviteMode === 'InvitePerson'){

                    if(!isset($U->MapAdmin[$email_bot]))
                        $U->MapAdmin[$email_bot] = [];

                    array_push($U->MapAdmin,$U->MapUsers[$Msg->email_to]);


                }

                sleep(5);
            }

        }


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

        unset($U->MapUsers[$email]);

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

    public function updateBot($botEmail){

        if (is_numeric($botEmail))
                               return;

        $botOrigin = $this->MapAdmin[$botEmail];

        $bot = Bot::where('email', $botEmail)->first();

        $bot->update([
            'rating'=>$botOrigin->rating,
            'arr_friends'=>json_encode($botOrigin->arrFriends,JSON_UNESCAPED_UNICODE)
        ]);

        $this->notifyAdmin();
    }

    public function addFriends()
    {
        /* get Friend */

        if (is_numeric($this->Msg->friend_email))
            $friendOrigin = $this->MapAdmin[$this->Msg->friend_email];
        else
            $friendOrigin = $this->MapUsers[$this->Msg->friend_email];

        $newFriend = $this->getFriendForArr($friendOrigin);

        /* get User */


        $userOrigin = $this->MapUsers[$this->Msg->email];
        $newUser = $this->getFriendForArr($userOrigin);


        array_push($userOrigin->arrFriends,$newFriend);
        array_push($friendOrigin->arrFriends,$newUser);
    }

    /*public function addUserFriend(){

        if(is_numeric($this->Msg->friend_email))
                                            return;

        $userOrigin = $this->MapUsers[$this->Msg->email];
        $newUser = $this->getFriendForArr($userOrigin);


        $friendOrigin = $this->MapUsers[$this->Msg->friend_email];
        $newFriend = $this->getFriendForArr($friendOrigin);


        array_push($userOrigin->arrFriends,$newFriend);
        array_push($friendOrigin->arrFriends,$newUser);

    }*/




}