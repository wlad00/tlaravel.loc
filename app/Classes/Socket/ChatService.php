<?php

namespace App\Classes\Socket;

use App\Classes\Socket\ChatSocket;
use Illuminate\Console\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatService
{

    public static function emailByConn($conn,$arrUsers){
        $email = null;

        foreach($arrUsers as $user){

            if($user->conn->resourceId == $conn->resourceId)
                        $email = $user->email;
        }

        return $email;
    }

    public static function getEmailsFriends($arrFriends){
        $arrEmails = [];

        foreach($arrFriends as $friend){

            array_push($arrEmails,$friend->email);
        }

        return $arrEmails;
    }


    public static function updateFriendsData(&$arrFriends,$arrUsers){


        for($i=0; $i<sizeof($arrFriends); $i++){

            $friend = $arrFriends[$i];


            if(is_numeric($friend->email)){

                $friend->enable = true;
                continue;
            }

            if(!isset($arrUsers[$friend->email])){

                $friend->enable = false;
//                $friend->block = true;
                continue;
            }

            $user = $arrUsers[$friend->email];

            $friend->enable = true;
            $friend->conn = null;
            $friend->name = $user->name;
            $friend->rating = $user->rating;
            $friend->block = $user->block;
        }
    }

    public static function saveLog($Msg){


    }
}