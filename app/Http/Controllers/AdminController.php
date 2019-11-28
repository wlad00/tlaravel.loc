<?php

namespace App\Http\Controllers;

//use App\Scale;
use App\Vocabulary\DeFr;
use App\Vocabulary\Deutsch;
use App\Vocabulary\EnDe;
use App\Vocabulary\EnEs;
use App\Vocabulary\EnFr;
use App\Vocabulary\English;
use App\Vocabulary\English6300;
use App\Vocabulary\EnRu;
use App\Vocabulary\EsDe;
use App\Vocabulary\EsFr;
use App\Vocabulary\French;
use App\Vocabulary\RuDe;
use App\Vocabulary\RuEs;
use App\Vocabulary\RuFr;
use App\Vocabulary\Russian;
use App\Vocabulary\Spanish;
use App\Vocabulary\Unique;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use App\Question;
//use App\Http\Controllers\Factories\factoryQuestions;
//use Symfony\Component\Console\Question\Question;
use InvalidArgumentException;
use PHPUnit\Exception;
use Statickidz\GoogleTranslate;


class AdminController extends Controller
{

    protected $vars=array();
    protected $showTabBox = false;
    protected $menu=array();
    protected $Num = 0;
    /*------------------------------*/
    protected $Nmax = 100;
    protected $INT_NUM = 100;
    protected $TIME_SLEEP = 20;
    /*------------------------------*/

    public function __construct()
    {

    }


//15 find ru-en
    public function makeFile15(){

        $dbFROM = new English;
        $dbTO = new French;
        $FILE = 'z_Fr.txt';
        $lan1 = 'fr';

        $isChange = '';
        $isChange = 'isChange';
        /*--------------------------------------*/
        $arrLAN = ['fr','de','es','ru','en'];

        $DBfrom = $dbFROM->setConnection('mysql2');
        $DBto = $dbTO->setConnection('mysql2');

        $ItemsTO = $DBto->all();

        $arrToOne = array();
        Storage::disk('admin_disk')->put($FILE, 'start');


        foreach($ItemsTO as $item){

            foreach($arrLAN as $lan){

                $str = (string)$item->$lan;

                $arrStr = explode(" ", $str);

                if(sizeof($arrStr)>1 && strlen(trim($str))!=0){

                    $itemEn = $DBfrom->where('en',$item->en)->get()->first();

                     if(!$itemEn ){ continue;}

                     if($itemEn->$lan1 != $item->$lan1  ){ continue;}

                         $arrToOne[$str]= $itemEn->$lan;
                     $string = $item->id.' '.$str.' => '.$itemEn->$lan;
                    echo $string.PHP_EOL;
                         Storage::disk('admin_disk')->append($FILE, $string);

                         if($isChange == 'isChange'){

                            $item->$lan = $itemEn->$lan;

                            $item->save();
                         }

                }
            }
        }

       /* $json = json_encode($arrToOne,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);*/

        return sizeof($arrToOne);


    }

//14 delete deleted
    public function makeFile14(){

        $db = new French;
        $LAN = 'fr';
        $isDelete = '';
        $isDelete = 'isDelete';

        /*-------------------*/
        $FILE ='del.txt';
        $DB = $db->setConnection('mysql2');

        $ArrItems = $DB->all();

        $json = Storage::disk('admin_disk')->get($FILE);

        $arrJSON = json_decode($json);
        $arrDel  = array();

        foreach($ArrItems as $item){

            if(in_array($item->$LAN, $arrJSON)) {

                $arrDel[] = $item->$LAN;
                echo $item->$LAN.PHP_EOL;

                if($isDelete = 'isDelete'){

                    $item->delete();
                }
            }
        }

        return sizeof($arrDel);

    }
//13 change to small letters
    public function makeFile13(){
        $db = new Russian;
        $LAN = 'fr';
        $isSave = '';
//        $isSave = 'isSave';
        /*----------------------*/
        $DB = $db->setConnection('mysql2');

        $ItemsOld = $DB->all();

        foreach($ItemsOld as $item){

            if($item->$LAN != mb_strtolower($item->$LAN)){
                echo $item->$LAN.'; ';
                $item->$LAN = mb_strtolower($item->$LAN);

                if($isSave == 'isSave'){

                    $item->save();
                }

            }
        }

    }

//12 find deleted
    public function makeFile12(){
        $dbOld = new English6300;
        $dbNew = new English;
        $LEN = 'en';

        $lenTO = 'fr';

        /*-------------------*/
        $FILE ='del.txt';
        $DBold = $dbOld->setConnection('mysql2');
        $DBnew = $dbNew->setConnection('mysql2');
        $isOne = function($str){

            $arrStr = explode(" ", $str);

            if(sizeof($arrStr) == 1){
                            return true; }
            else {
                return false; }
        };


        $ItemsOld = $DBold->all();
        $ItemsNew = $DBnew->all();

        $arrNew = array();

        foreach($ItemsNew as $item){
            $arrNew[] = $item->$LEN; }

       $ArrDel = array();

        Storage::disk('admin_disk')->put($FILE, 'start');
        foreach($ItemsOld as $itemOld){

            if(!in_array($itemOld->$LEN, $arrNew) && $isOne($itemOld->$lenTO)) {

                $ArrDel[]=$itemOld->$lenTO;
                /*Storage::disk('admin_disk')
                    ->append($FILE,$itemOld->$LEN/*.';  '.$itemOld->ru.';  '.*/
                       /* $itemOld->ru.';  '.$itemOld->de.';  '
                .$itemOld->fr*/
               /* echo $itemOld->$LEN.PHP_EOL;*/
            }
        }

        sort($ArrDel);
//        foreach($ArrDel as $str){
//            echo $str.PHP_EOL;
//            Storage::disk('admin_disk')
//                ->append($FILE,$str);
//        }

        $json = json_encode($ArrDel,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return sizeof($ArrDel);
    }

//11 find moreThenOne
    public function makeFile(){
//        $db = new English6300;
        $dbNew = new English;
//        $LAN = 'ru';
        $isDel = '';
//        $isDel = 'isDel';
        /*---------------------------------------*/
        $arrLAN = ['fr','de','es','ru','en'];
        $FILE ='del.txt';


//        $DB = $db->setConnection('mysql2');
        $DBnew = $dbNew->setConnection('mysql2');

//        $ItemsOld = $DB->all();
        $ItemsNew = $DBnew->all();

        $arrMore = array();
        $arrToOne = array();

        foreach($ItemsNew as $item){

            foreach($arrLAN as $lan){

                $str = (string)$item->$lan;

                $arrStr = explode(" ", $str);

                if(sizeof($arrStr)>1 || strlen(trim($str))==0 ){


                    array_push( $arrMore, $item->id);

                    if($isDel == 'isDel'){

                        $item->delete();
                    }


                    break;

                }
            }

        }
//        sort($arrRu);


        $json = json_encode($arrMore,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return sizeof($arrMore).' ; '. sizeof($arrToOne);
    }

//10 translate from DB
    public function makeFile10(){

        $dbTO = new French;
        $dbFROM = new DeFr;// en<-es trend_2
        $trend = 'trend_2';
        $lenFROM = 'fr';
        $lenTO = 'de';
        $isTranslate = '';
        $isTranslate = 'isTranslate';
/*--------------------------------------*/

        $DBto = $dbTO->setConnection('mysql2');
        $DBfrom = $dbFROM->setConnection('mysql2');

        $ItemsTO = $DBto->all();
        $ItemsFROM = $DBfrom->all();

        $arrFROM = array();
        $arrItemsTRANS = array();

        foreach($ItemsFROM as $item){

            if($item->$trend == 1){
                        $arrFROM[] = $item->$lenFROM;
                        $arrItemsTRANS[$item->$lenFROM] = $item->$lenTO;
            }
        }


        $i=0;
        foreach($ItemsTO as $item){

            if(in_array($item->$lenFROM, $arrFROM)) {

                $item->$lenTO = $arrItemsTRANS[$item->$lenFROM];

                $i++;

                if($isTranslate == 'isTranslate'){
                    $item->save();
                }
            }
        }

        return 'translated = '.$i;

    }

//9 delete, if noLetter in string
    public function makeFile9(){

        $DB = new French;
        $LAN = 'fr';
        $FILE ='del.txt';
        $isDel = '';
//        $isDel = 'isDel';
        /*-------------------------*/

        $enRu = $DB->setConnection('mysql2');

        $Arr = $enRu->all();

        $arrDel = array();

        foreach($Arr as $item){

//            if(preg_match("/-/i", (string)$item->$LAN)) {
            if(preg_match("/[^\w]/ui", (string)$item->$LAN)) {

                array_push($arrDel, $item->$LAN);

            }
        }

        if($isDel == 'isDel'){

            foreach($arrDel as $word){

                $item = $DB->where($LAN,$word)->first();

                $item->delete();
            }

        }

        $json = json_encode($arrDel,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return $json;
    }
    //8 delete double
    public function makeFile8(){

        $db = new French;
        $LAN = 'fr';
        $FILE ='del.txt';
        $isDel = '';
        $isDel = 'isDel';
        /*-------------------------*/

        $DB = $db->setConnection('mysql2');

        $ArrItems = $DB->all();
        $ArrWords = array();
        $arrDel = array();

        foreach($ArrItems as $item){
            $ArrWords[]=$item->$LAN;
        }
        $ArrCount = array_count_values($ArrWords);

        foreach($ArrCount as $key => $value){

            while($value > 1){
                $arrDel[] = $key;
                $value--;
            }
        }

        if($isDel == 'isDel'){

            foreach($arrDel as $word){

                $item = $DB->where($LAN,$word)->first();

                $item->delete();
            }

        }

        $json = json_encode($arrDel,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return $json;
    }

    //7 delete moreThenOne + isDel
    public function makeFile7(){
        $db = new French;
        $LAN = 'fr';
        $FILE ='del.txt';
        $isDel = '';
        $isDel = 'isDel';
        /*---------------------------------------*/

        $DB = $db->setConnection('mysql2');

        $Arr = $DB->all();

        $arrMore = array();


        foreach($Arr as $item){

            $str = (string)$item->$LAN;

            $arrStr = explode(" ", $str);

            if(sizeof($arrStr)>1){

                array_push( $arrMore, $str);

                if($isDel == 'isDel'){

                    $item->delete();
                }
            }
        }
//        sort($arrRu);


        $json = json_encode($arrMore,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return $json;
    }

    //6  make UNIQE from array [DB...] to DB4
    public function makeFile6(){

//En        $fromDB = [new EnFr, new EnRu, new EnDe, new EnEs];
//Ru        $fromDB = [new English, new EnRu, new RuEs, new RuDe, new RuFr];
//Es        $fromDB = [new English, new EnEs, new RuEs, new EsDe, new EsFr];
//De        $fromDB = [new English, new EnDe, new RuDe, new EsDe, new DeFr];
//        $fromDB = [new English, new EnFr, new RuFr, new EsFr, new DeFr];
        $fromDB = [new French];
        $toDB = new Unique;
        $LAN = 'fr';
        $isInsert = '';
        $isInsert = 'isInsert';
        /*---------------------------------------*/


        $DBfrom = array();

        foreach($fromDB as $db){

            array_push($DBfrom, $db->setConnection('mysql2'));
        }


        $DBto = $toDB->setConnection('mysql2');


        $ARRfrom = array();
        /*---------------*/
        for($i=0; $i<sizeof($DBfrom);$i++){

            $DBall = $DBfrom[$i]->all();

            foreach($DBall as $item){

//                $str = mb_strtolower((string)$item->$LAN);
//for Deutch
            $str = (string)$item->$LAN;

                array_push( $ARRfrom, $str);
            }

            $ARRfrom = array_unique($ARRfrom);
        }

        sort($ARRfrom);

        $ARRto = array();
        foreach($ARRfrom as $str){

            array_push($ARRto, [ $LAN => $str ]);

        }

//        sort($ARRto);

       /* $ARRto = [
          [ 'en'=>'first'],
          [ 'en'=>'second']
        ];*/
        $json = json_encode($ARRfrom,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put('del.txt', $json);

        if($isInsert == 'isInsert'){
                        $DBto::insert($ARRto);  }

        return $json;
    }


    //5  del article from DB

    // Fr [a ]
    // [la ], [le ],[un ], [en ], [de ], [du ]
    // [les ], [une ], [des ] ,
    // [s'], [l']
    // De [zu ]
    // [ein ][der ] [das ][die ]
    // [eine ]
    //  [einen ]  [diese ]
    // [dieser ]
    // Es [a ]
        // [la ] [lo ] [el ] [un ]
        // [una ] [las ] [los ]
    // Eng -> [a ],
    // [an ] [to ]
    //
    // [the ]

    public function makeFile5(){

        $strDel = "dieser ";
//        $strDel2 = "diese ";
//        $strDel3 = "des ";
//
//        $strDel4 = "ein ";
//        $strDel5 = "der ";
//        $strDel6 = "das ";
//        $strDel7 = "die ";
//
//        $strDel8 = "una ";
//        $strDel9 = "las ";
//        $strDel10 = "los ";
//
//        $strDel11 = "the ";



        $DB = new Spanish;
//        $LAN = 'es';

        $isChange = '';
        $isChange = 'isChange';
        /*-------------------------*/
        $FILE = 'del.txt';
        $arrLAN = ['fr','de','es','ru','en'];


        $len = strlen($strDel);

        $enRu = $DB->setConnection('mysql2');

        $Arr = $enRu->all();

        $arrDel = array();

        foreach($Arr as $item){

            foreach($arrLAN as $Lan) {

                $substr = substr((string)$item->$Lan, 0, $len);

                if ($substr == $strDel
//                    || $substr == $strDel2
//                     || $substr == $strDel3 || $substr == $strDel4
//                     || $substr == $strDel5 || $substr == $strDel6
//                     || $substr == $strDel7 || $substr == $strDel8
//                     || $substr == $strDel9 || $substr == $strDel10
//                                             || $substr == $strDel11
                ) {


                    $substr = substr((string)$item->$Lan, $len);
                    echo $item->$Lan .' ('.$Lan.') '.$item->id .'; '.PHP_EOL;
                    array_push($arrDel, $substr.' ('.$Lan.') '.$item->id);

                    $item->$Lan = $substr;

                    if ($isChange == 'isChange') {

                        $item->save();
                    }

                }
            }

        }

        $json = json_encode($arrDel,JSON_UNESCAPED_UNICODE);
        Storage::disk('admin_disk')->put($FILE, $json);

        return sizeof($arrDel);
    }

//4  make file from DB / 2)ifFindEmpty
    public function makeFile4(){

        $DB = new French;
        $lenFROM = 'fr'; $lenTO = 'de';
        $FILE ='1000frAdd.txt';
        $isFindEmpty = 'isFindEmpty';
        /*---------------------------------------*/

        $enRu = $DB->setConnection('mysql2');

        $ArrItems = $enRu->all();

        $arrRu = array();


        foreach($ArrItems as $item){

//            $str = mb_strtolower((string)$item->$LAN);
            $str = (string)$item->$lenFROM;

            if($isFindEmpty != 'isFindEmpty' || !$item->$lenTO){
                                    array_push( $arrRu, $str);  }

        }

        sort($arrRu);


        $json = json_encode($arrRu,JSON_UNESCAPED_UNICODE);

        Storage::disk('admin_disk')->put($FILE, $json);

        return $json;
    }


    //3 from txt to DB translate , change source FILE
    public function makeFile3(){

        $FILE = '1000frAdd.txt';
//        $FILE = 'del.txt';

        // 1 en_fr,
//                  $FILE = '8127en.txt';
        // 2 ru_de,
//                  $FILE = '6500ru.txt';
        // 3 ru_fr,
//                  $FILE = '6807ru.txt';
        // 4 es_de,
//                  $FILE = '6442es.txt';
        // 5 es_fr
//                  $FILE = '6442es.txt';
        // 6 de_fr,
//                  $FILE = '8127de.txt';

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

            if($this->Num > $this->Nmax){
                echo $this->Nmax.'; ';

                try{
                    $stor = Storage::disk('admin_disk')->get('log.txt');
                    sleep(1);
                    Storage::disk('admin_disk')
                        ->put('log.txt', $stor.'; '.$this->Nmax);
                }
                catch(\Exception $e){
//                    $str = ' ErrorException ;';
                    echo PHP_EOL.'Error storage = '.$this->Nmax.PHP_EOL;
//                    echo $e;
//                    Storage::disk('admin_disk')->append('log.txt',$str );
                }


                sleep($this->TIME_SLEEP);
                $this->Nmax += $this->INT_NUM;
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

        Storage::disk('admin_disk')->append('log.txt','end;\n' );

        return ' sizof(Arr) = '.sizeof($Arr).' ; ';

    }
    /*---------private---------------*/
    // need to change EnEs -> RuEs(2), (4), (6)
    private function loopFn($word, $trend){

        $DB = new French;  $FROM = 'fr'; $TO = 'de';
        $isAddTo4 = 'isAddTo4';

        $trend = 'trend_1'; // if isAddTo4 any trend
        /*----------------------------------------*/



        // 1 en_fr,
//                  $DB = new EnFr;  $FROM = 'en'; $TO = 'fr';
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
        $isContainFrom = function($enRu,$langFrom, $word, $trend, $isAddTo4, $langTo){

            if($isAddTo4 == 'isAddTo4'){

                $item = $enRu->where($langFrom, $word)->get()->first();

                if(!$item){
                    return 'noThisFrom';
                }
                else{
                    return $item->$langTo;   }

            }
            else{

            $wordFrom = $enRu->where($langFrom, $word)->where($trend, 1)->get()->first();

            return $wordFrom;
            }

        };
        $isItemWithBoth = function($enRu, $langFrom, $word, $langTo, $wordTrans){
            return $enRu->where($langFrom, $word)->where($langTo, $wordTrans)->get()->first();
        };
        $saveTrend = function($item, $trend){

            $item->$trend = 1;
            $item->save();

            return null;
        };
        $saveAddTo4 = function($enRu, $langFrom, $word, $langTo, $wordTrans){

            $item = $enRu->where($langFrom, $word)->get()->first();

            $item->$langTo = $wordTrans;
            $item->save();

            return true;
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
        if($isContainFrom($enRu,$langFrom, $word, $trend, $isAddTo4,$langTo)){

                   return 'isPresent';
        }

        //3

        $strTrans = null;
        for($i = 0; $i <20; $i++){

            $strTrans = $trans->translate($langFrom, $langTo, $word);

            if($strTrans){
                    break; }

            $log = 'Error tr ('.$word.') = '.($i+1).' ('.$this->Num.') '.(60*($i+1)).'sec; ';

            echo $log.PHP_EOL;

//            $stor = Storage::disk('admin_disk')->get('log.txt');
//            Storage::disk('admin_disk')->put('log.txt', $stor.$log);


            try{
            Storage::disk('admin_disk')->append('log.txt', $log);
            }
            catch(\Exception $e){
                echo PHP_EOL.'Error storage ; '.$log.PHP_EOL;
            }

            sleep(60*($i+1));
        }


        if(!$strTrans){

            echo 'Error loopFn()/translate return isError ; ';
            return 'isError';
//            throw new Exception("Google 48 hours)");
        }


        $this->Num++;

        $wordTrans = $clearStr($strTrans);

        if($isAddTo4 == 'isAddTo4'){

            $saveAddTo4($enRu, $langFrom, $word, $langTo, $wordTrans);

            return 'isPresent';
        }


        //2)
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
