<?php

namespace App\Classes\Socket\Workers;


use App\Classes\Socket\ChatService;
use App\Classes\Socket\Singletons\SingleP;
use App\Classes\Socket\Singletons\SingleU;

class WorkerSend
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

    public function sendToUser(){

        if(is_numeric($this->Msg->email_to))
                                        return;
        /*--------------------------------------*/

        if(!isset($this->singleU->MapUsers[$this->Msg->email_to]))
            return;

        $conn = $this->singleU->MapUsers[$this->Msg->email_to]->conn;

        $conn->send( json_encode(
                $this->Msg,JSON_UNESCAPED_UNICODE
            )
        );

    }

    /**
     * @throws \Exception
     */
    public function sendToBot(){

        /*if(!is_numeric($this->Msg->email_to))
                                        return;*/

        /*if($this->Msg->TypeStep != 'text')
                                        return;*/
        /*--------------------------------------*/

        if(!$this->singleU->admin)
                                return;

        /*--------------------------------------*/

        $this->singleU->admin->conn->send( json_encode(
                $this->Msg,JSON_UNESCAPED_UNICODE
            )
        );


    }
    public function returnAgreeBot(){

        if(!is_numeric($this->Msg->email_to))
                                        return;

        if($this->Msg->TypeStep != '1_invite')
                                        return;
        /*--------------------------------------*/


        $this->Msg->TypeStep = '3_agree';

        $email_bot = $this->Msg->email_to;
        $this->Msg->email_to = $this->Msg->email_from;
        $this->Msg->email_from = $email_bot;

        sleep(5);


        if(!isset($this->singleU->MapUsers[$this->Msg->email_to]))
                                                                return;

        $conn = $this->singleU->MapUsers[$this->Msg->email_to]->conn;

        $conn->send( json_encode(
                $this->Msg
            )
        );

    }


    public function sendMsg(){


        if(is_numeric($this->Msg->email_to)){


            if($this->Msg->TypeStep == 'text'){

                $this->Msg->email_bot = $this->Msg->email_to;
                $this->Msg->email_to = 'admin@www.www';
            }


            if($this->Msg->TypeStep == '1_invite'){

                $this->Msg->TypeStep = '3_agree';
                $email_bot = $this->Msg->email_to;
                $this->Msg->email_to = $this->Msg->email_from;
                $this->Msg->email_from = $email_bot;

                if($this->Msg->InviteMode === 'InvitePerson'){

                    if(!isset($this->singleU->MapAdmin[$email_bot]))
                        $this->singleU->MapAdmin[$email_bot] = [];

                    array_push($this->singleU->MapAdmin,$this->singleU->MapUsers[$this->Msg->email_to]);


                }

                sleep(5);
            }

        }


        if(!isset($this->singleU->MapUsers[$this->Msg->email_to]))
            return;

        $conn = $this->singleU->MapUsers[$this->Msg->email_to]->conn;

        $conn->send( json_encode(
                $this->Msg
            )
        );
    }


}
