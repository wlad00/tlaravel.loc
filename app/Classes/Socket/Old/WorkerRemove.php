<?php

namespace App\Classes\Socket\Workers;


use App\Classes\Socket\ChatService;
use App\Classes\Socket\Singletons\SingleP;
use App\Classes\Socket\Singletons\SingleU;

class WorkerRemove
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

    public function removeFriend(){

        $user = $this->singleU->MapUsers[$this->Msg->email];

        $user->arrFriends = array_filter($user->arrFriends, function($friend) {
            return $friend->email != $this->Msg->email_removed;
        });
    }


}
