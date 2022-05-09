<?php

namespace App\Classes\Socket\Singletons;


use App\Classes\Socket\ChatService;
use App\CONSTANT;
use App\Models\Bot;

class SingleP{

    private $AllBots=[];
    public $arrBots=[];

    private $MapPersons = [];

    private $arrPersons = [],$arrIndexes=[];
    private $MapUsers = [];

    private static $instance = null;
    /**
     * Persons constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $this->AllBots = Bot::where('id','<',10)->get(['email','name','rating'])->toArray();


        $this->makeArrIndexes();

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

        $this->MapUsers = $MapUsers;

    }


    /*public function randomArrBots(){

        $arrBots = [];

        foreach($this->arrIndexes as $ind){

            $this->AllBots[$ind]['enable']=true;

            array_push($arrBots,(object)$this->AllBots[$ind]);
        }

        $this->arrBots = $arrBots;

        ChatService::echo_arr($arrBots,'/$arrBots---');

    }*/

    /*--------------------------------*/

    /*public function newBotsToPersons(){


        foreach($this->arrBots as $bot){

            if(!isset($this->MapPersons[$bot->email]))
                        $this->MapPersons[$bot->email]=$bot;
        }
    }
    public function newUsersToPersons(){

        foreach($this->MapUsers as $email=>$user){

            if(!isset($this->MapPersons[$email]))
                    $this->MapPersons[$email]=$user;
        }


    }*/
    public function makeArrPersons(){

        $arrPersons = [];

        foreach($this->MapUsers as $email=>$user){

            if(!$user->block)
                    array_push($arrPersons,$user);
        }
    }
    /*public function removeBlockedPersons(){


        ChatService::echo_arr($this->MapPersons,'/MapPersons--------');
        ChatService::echo_arr($this->MapUsers,'/MapUsers--------');

        foreach($this->MapPersons as $email=>$person){

            if($this->MapUsers[$email]->block)
                unset($this->MapPersons[$email]);

        }

    }*/

    /**
     * @return array
     * @throws \Exception
     */
    public static function getMapPersons(){

        $selfP = self::getInstance();

        return $selfP->MapPersons;
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