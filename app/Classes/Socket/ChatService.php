<?php

namespace App\Classes\Socket;

use App\Classes\Socket\ChatSocket;
use Exception;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ChatService
{
    private static function echo_line($elem){

        foreach($elem as $key=>$item)

            if(gettype($item)=='string' || gettype($item)=='integer'|| gettype($item)=='boolean')
                echo $key.'=>'.$item.', ';
            else
                echo $key.'=> array(...) ';
//                echo json_encode($item);
        echo "\n";
    }

    /**
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function readStorage($file){


        try {
            $json = Storage::disk('admin_disk')->get($file);
        } catch (FileNotFoundException $e) {
            $json = (object)[];
        }

        return json_decode($json);

    }

    /**
     * @param $arr
     * @param $name_arr
     */
    public static function echo_arr($arr, $name_arr){
        echo $name_arr." => \n";

        foreach($arr as $elem){

                self::echo_line($elem);

        }
    }


    public static function appendStorage($file,$text){

        try{

            Storage::disk('admin_disk')->append($file, date('d.m.y  H:i:s').'  '.$text
                .PHP_EOL.PHP_EOL);
        }
        catch(Exception $e ){

        }


    }

    public static function emailByConn($conn,$arrUsers){
        $email = null;

        foreach($arrUsers as $user){

            if($user->conn->resourceId == $conn->resourceId)
                        $email = $user->email;
        }

        return $email;
    }
    public static function getFriendForArr($friendOrigin){

        $newFriend = (object)[];
        $newFriend->email = $friendOrigin->email;
        $newFriend->name = $friendOrigin->name;
        $newFriend->rating = $friendOrigin->rating;
        $newFriend->block = $friendOrigin->block;
        $newFriend->enable = true;

        return $newFriend;
    }

    public static function getEmailsFriends($arrFriends){
        $arrEmails = [];

        foreach($arrFriends as $friend){

            array_push($arrEmails,$friend->email);
        }

        return $arrEmails;
    }


    /*public static function updateArrFriends(&$singleU){
//        $emailsFriends = $singleU->objMsg->emailsFriends;

        $MapUsers = $singleU->MapUsers;
        $arrFriends = $singleU->objMsg->arrFriends;
        $arrFriendsNew = [];


        foreach($arrFriends as $friend){

            if(!in_array($friend->email,$emailsFriends))
                                                continue;

            if(is_numeric($friend->email)){

                $friend->enable = true;

                array_push($arrFriendsNew,$friend);

                continue;
            }
            if(!isset($MapUsers[$friend->email])){

                $friend->enable = false;

                array_push($arrFriendsNew,$friend);

                continue;
            }
            $user = $MapUsers[$friend->email];

            $friend->enable = true;

            $friend->name = $user->name;
            $friend->rating = $user->rating;
            $friend->block = $user->block;

            array_push($arrFriendsNew,$friend);
        }

        $singleU->Msg->arrFriends = $arrFriendsNew;



    }*/

    public static function saveLog($Msg){


    }
}
