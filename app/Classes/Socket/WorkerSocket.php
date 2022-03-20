<?php

namespace App\Classes\Socket;


class WorkerSocket{

    private $TypeMsg,$Name;
    private $activeUsers = [];
    private $conn;
    private $arrMails;
    private $arrMailConn;

    public function __construct($conn, $msg)
    {
        $Msg = json_decode($msg);

        $this->TypeMsg = $Msg->type;

        $this->Name = $Msg->name;

        $this->Mail = $Msg->email;

        $this->conn = $conn;

        $this->Id = $conn->resourceId;
    }

    public function login(){

        if ($this->TypeMsg != "login") return;

//        $this->activeUsers[$this->conn->resourceId] = $this->Name;

        $this->arrMailConn[$this->Mail] = $this->conn;

//        property_exists($this->arrMailConn, $email);

        $this->updateSocketId();
    }

    public function updateSocketId(){

        /*$data = $this->database->select('socket_id', [
            'user'
                    ],
            [
            'user' => $this->Name
        ]);
        if(empty($data)){
            // insert
            //echo 'Insert';
            $this->database->insert('socket_id', [
                'user' => $user,
                'socket_id' => $id
            ]);
        }else{
            // update
            //echo 'Update';
            $data = $this->database->update('socket_id', [
                'socket_id' => $id
            ], [
                'user' => $user
            ]);
        }*/
    }



}