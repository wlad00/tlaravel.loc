<?php

namespace App\Classes\Socket;


use App\CONSTANT;

class SingleP{

    private $arrPersons = [],$arrIndexes=[];
    private $arrUsers = [];

    private static $instance = null;
    /**
     * Persons constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->makeArrIndexes();

//        $this->makeArrPersons();


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

    public function setArrUsers($arrUsers){

        $arrUsersP = [];

        foreach($arrUsers as $user){

            array_push($arrUsersP,
                [$user->name,$user->rating,$user->email]);
        }

        $this->arrUsers = $arrUsersP;
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

        return array_merge($this->arrPersons,$this->arrUsers) ;
    }
    public function getArrIndexes(){

        return $this->arrIndexes;
    }

    /**
     * @return SingleP|null
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

}