<?php

namespace App\Http\Controllers;

//use App\Scale;
use App\Vocabulary\DeFr;
use App\Vocabulary\EnDe;
use App\Vocabulary\EnEs;
use App\Vocabulary\EnFr;
use App\Vocabulary\EnRu;
use App\Vocabulary\EsDe;
use App\Vocabulary\EsFr;
use App\Vocabulary\RuDe;
use App\Vocabulary\RuEs;
use App\Vocabulary\RuFr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use App\Question;
//use App\Http\Controllers\Factories\factoryQuestions;
//use Symfony\Component\Console\Question\Question;
use PHPUnit\Exception;
use Statickidz\GoogleTranslate;


class AdminController extends Controller
{

    protected $vars=array();
    protected $showTabBox = false;
    protected $menu=array();
    protected $Num = 0;

    public function __construct()
    {

    }
    //5  remove [a ] [la ] [el ] [un ] [una ] from DB
    public function makeFile5(){

        $len = 2; $strDel = 'a ';
        /*-------------------------*/

        $enRu = (new RuEs)->setConnection('mysql2');

        $Arr = $enRu->all();



        foreach($Arr as $item){

            $substr = substr((string)$item->es,0,$len);

            if ($substr == $strDel){

                $substr = substr((string)$item->es,$len);
                echo $item->es.'; ';

                $item->es = $substr;

                $item->save();
            }


        }
    }

//4  make file from DB
    public function makeFile4(){

        $DB = new EnDe;
        $LAN = 'en';
        $FILE ='80000000en.txt';
        /*---------------------------------------*/

        $enRu = $DB->setConnection('mysql2');

        $Arr = $enRu->all();

        $arrRu = array();



        foreach($Arr as $item){

            $str = mb_strtolower((string)$item->$LAN);

           array_push( $arrRu, $str);
        }

        sort($arrRu);


        $json = json_encode($arrRu,JSON_UNESCAPED_UNICODE);

        Storage::disk('admin_disk')->put($FILE, $json);

        return $json;
    }


    //3 from txt to DB translate , change source FILE
    public function makeFile(){

        $FILE = '10-2.txt';

        // 1 en_fr,
        //          $FILE = 8127en.txt
        // 2 ru_de,
        //          $FILE = 6500ru.txt
        // 3 ru_fr,
        //          $FILE = 6500ru.txt
        // 4 es_de,
        //          $FILE = 6442es.txt
        // 5 es_fr
        //          $FILE = 6442es.txt
        // 6 de_fr,
        //          $FILE = 8127de.txt
        /*----------------------*/

        $json = Storage::disk('admin_disk')->get($FILE);
        $Arr = json_decode($json);



        function changeTrend($trend){
            if($trend == 'trend_1'){
                            return 'trend_2'; }
            else{
                return 'trend_1';}
        }


        for($i = 0; $i<sizeof($Arr); $i++){
            $word = $Arr[$i];
            $trend = 'trend_1';

            if(($this->Num+1) % 70 == 0){
                echo $Arr[$i].' ('.$this->Num.',30s); ';
                sleep(30);
            }

            $wordTrans = $this->loopFn($word,$trend);

            $n=0;
            while($wordTrans != 'isError' && $wordTrans != 'isPresent' && $n<20){
                $n++;
                $trend = changeTrend($trend);

                $wordTrans = $this->loopFn($wordTrans,$trend);

            }
            if($n>19){
                $i = sizeof($Arr)+100;
                echo "Error n > 19";
            }
            if($wordTrans == 'isError'){
                $i = sizeof($Arr)+100;
                echo "Error translate()->loopFn()->wordTrans == Null";


            }
        }



        return ' sizof(Arr) = '.sizeof($Arr).' ; ';

    }
    /*---------private---------------*/
    // need to change EnEs -> RuEs(2), (4), (6)
    private function loopFn($word, $trend){


        // 1 en_fr,
                  $DB = new EnFr;  $FROM = 'en'; $TO = 'fr';
        // 2 ru_de,
//                  $DB = new RuDe;  $FROM = 'ru'; $TO = 'de';
        // 3 ru_fr,
//                  $DB = new RuFr;  $FROM = 'ru'; $TO = 'fr';
        // 4 es_de,
//                  $DB = new EsDe;  $FROM = 'es'; $TO = 'de';
        // 5 es_fr
//                  $DB = new EsFr;  $FROM = 'es'; $TO = 'fr';
        // 6 de_fr,
//                  $DB = new DeFr;  $FROM = 'de'; $TO = 'fr';
        /*----------------------*/
        /*---------------------------------*/

        $trans = new GoogleTranslate();
        $enRu = $DB->setConnection('mysql2');
        $checkLangFrom = function($trend,$FROM,$TO){
            return $trend == 'trend_1' ? $FROM:$TO; };
        $checkLangTo = function($trend,$FROM,$TO){
            return $trend == 'trend_1' ? $TO:$FROM; };
        $isContainFrom = function($enRu,$langFrom, $word, $trend){

            $wordFrom = $enRu->where($langFrom, $word)->where($trend, 1)->get()->first();

            return $wordFrom;
        };
        $isItemWithBoth = function($enRu, $langFrom, $word, $langTo, $wordTrans){
            return $enRu->where($langFrom, $word)->where($langTo, $wordTrans)->get()->first();
        };
        $saveTrend = function($item, $trend){

            $item->$trend = 1;
            $item->save();

            return null;
        };
        $createItem = function($enRu, $langFrom, $langTo, $word, $wordTrans, $trend){

            $enRu->create([
                $langFrom=>$word,
                $langTo=>$wordTrans,
                $trend=>1
            ]);

            return $wordTrans;
        };
        $getLongest = function($strTrans){

            $arrStr = explode(" ",$strTrans);
            $maxLen = 0;
            $strLongest = '';
            foreach($arrStr as $str){
                if(strlen($str)>$maxLen){
                    $strLongest = $str;
                    $maxLen = strlen($str);
                }
            }
//            return $strLongest;
            return $strTrans;
        };

        $clearStr = function($strTrans){

            $arrStr = explode(";",$strTrans);
            $arrStr = explode(".",$arrStr[0]);
            $arrStr = explode(",",$arrStr[0]);

            return $arrStr[0];
        };

        //1
        $langFrom = $checkLangFrom($trend,$FROM,$TO);
        $langTo = $checkLangTo($trend,$FROM,$TO);

        //2
        if($isContainFrom($enRu,$langFrom, $word, $trend)){

                   return 'isPresent';
        }

        //3

        $strTrans = null;
        for($i = 0; $i <20; $i++){

            $strTrans = $trans->translate($langFrom, $langTo, $word);

            if($strTrans){
                    break; }

            $log = 'Error translate = '.($i+1).' ('.$this->Num.'); ';

            echo $log.PHP_EOL;

//            $stor = Storage::disk('admin_disk')->get('log.txt');
//            Storage::disk('admin_disk')->put('log.txt', $stor.$log);
            Storage::disk('admin_disk')->append('log.txt', $log);

            sleep(60*($i+1));
        }


        if(!$strTrans){

            echo 'Error loopFn()/translate return isError ; ';
            return 'isError';
//            throw new Exception("Google 48 hours)");
        }


        $this->Num++;

        $wordTrans = $clearStr($strTrans);

        //1)
        $item = $isItemWithBoth($enRu, $langFrom, $word, $langTo, $wordTrans);


        if ($item) {
                $saveTrend($item, $trend);
                                    return 'isPresent';}
        else{
            return $createItem($enRu, $langFrom, $langTo, $word, $wordTrans, $trend);}

    }

// from tags
    public function makeFile1(){
        $Arr = array();
        $text = Storage::disk('admin_disk')->get('5000-tags.txt');
        $arr1 = explode("</td><td>",$text);

        foreach($arr1 as $str){

            // only one word
            $str = preg_replace("/ .+/i", "", $str);
            //without (...)
//            $str = preg_replace("/\(.+\)/i", "", $str);
            $str = trim($str);
            if(!preg_match('/[><]/', $str) && preg_match('/[a-z]/', $str) ){
                array_push($Arr, $str);
            }
        }

        $json = json_encode($Arr);
        Storage::disk('admin_disk')->put('5000.txt', $json);

    }

    public function index(){
//        $this->vars = array_add($this->vars,'title','Admin Panel');




        return view('admin/admin')->with($this->vars);
    }



}
