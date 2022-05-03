<?php

namespace App\Classes\Socket\Singletons;


use App\CONSTANT;
use App\Models\Person;

class SingleP{

    public $arrBots;
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

        $this->arrBots = Person::where('id','<',10)->get();

//        sizeof($this->arrBots);
       /* $this->arrBots = array_slice($this->arrBots,0,3);

        $this->arrBots = (object)$this->arrBots;*/

    }

    public static function readArrPersons(){



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

        $this->arrIndexes = [0,1,2,3,4];
                                    return;

        $size = sizeof($this->arrBots)-1;

        $arrIndexes = [];

        for($i=0; $i<5; $i++){


            $rand = random_int(0, $size);

            if(in_array($rand,$arrIndexes))
                $i--;
            else
                array_push($arrIndexes,$rand);
        }

        $this->arrIndexes = $arrIndexes;
    }


    public function setArrUsers($MapUsers){

        $this->arrUsers = $MapUsers;

    }


    private function makeArrPersons(){

//        $arrBots = CONSTANT::ARR_BOTS;

        $arrPersons = [];

        foreach($this->arrIndexes as $ind){

            $this->arrBots[$ind]['enable']=true;

            array_push($arrPersons,$this->arrBots[$ind]);
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