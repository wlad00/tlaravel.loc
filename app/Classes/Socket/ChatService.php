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

    public static function updateFriends(&$arrFriends,$arrUsers){

        for($i=0;$i<sizeof($arrFriends);$i++){

            $friend = $arrFriends[$i];
            $user = $arrUsers[$friend->email];

            $friend->enable = $user??false;

            if(!$user) continue;

            $friend->conn = $user->conn;
            $friend->name = $user->name;
            $friend->rating = $user->rating;
            $friend->block = $user->block;

        }


    }
}
