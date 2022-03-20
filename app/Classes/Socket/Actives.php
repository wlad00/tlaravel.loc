<?php

namespace App\Classes\Socket;


class Actives{

    private $arrActives = [];
    private $linksNotify = [];
    private $linksConn = [];

    private static $instance = null;
    /**
     * Persons constructor.
     * @throws \Exception
     */
    public function __construct()
    {

    }

    /**
     * @param $arrFriends
     * @throws \Exception
     */
    public static function addToArrActives($Msg,$conn)
    {
        $A = static::getInstance();

        $A->arrActives[$Msg->email]=$Msg->arrFriends;

        foreach($Msg->arrFriends as $friend){

            if(!isset($A->linksNotify[$friend[2]]))
                $A->linksNotify[$friend[2]] = [];

            if(!in_array($Msg->email,$A->linksNotify[$friend[2]]))

                array_push($A->linksNotify[$friend[2]],$Msg->email);

        }

        $A->linksConn[$Msg->email]=['conn'=>$conn,'name'=>$Msg->name,'rating'=>$Msg->rating,'invite'=>$Msg->invite];

//        echo json_encode($Actives->arrActives,JSON_UNESCAPED_UNICODE);
        echo json_encode($A->linksNotify,JSON_UNESCAPED_UNICODE);
        echo json_encode($A->linksConn,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $email
     * @throws \Exception
     */
    public static function notifyUsers($email){

        $Actives = static::getInstance();
        //1
        $emailUser =$Actives->linksNotify[$email];

    }

    /**
     * @return Actives|null
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