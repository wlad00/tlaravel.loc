<?php

namespace App\Classes\Socket\Workers;


use App\Classes\Socket\ChatService;
use App\Classes\Socket\Singletons\SingleP;
use App\Classes\Socket\Singletons\SingleU;

class WorkerAdd
{

    private $Msg;

    private $singleU;


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

    public function addFriends()
    {
        /* get Friend */

        $friendOrigin = $this->singleU->MapUsers[$this->Msg->friend_email];
        $newFriend = ChatService::getFriendForArr($friendOrigin);

        /* get User */


        $userOrigin = $this->singleU->MapUsers[$this->Msg->email];
        $newUser = ChatService::getFriendForArr($userOrigin);


        array_push($userOrigin->arrFriends,$newFriend);
        array_push($friendOrigin->arrFriends,$newUser);
    }


}
