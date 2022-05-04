<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model

{

    protected $table = 'bots';

    protected $fillable = ['name','email','rating','arr_friends'];

    public $timestamps = false;

    /*-------------------------------------------*/


    public static function updateBot($friend){

        if(!is_numeric($friend->email))
                                    return;

        /*-----------------------------------*/

        echo "5 \n";

        $bot = static::where('email', $friend->email)->first();

        echo "6 \n";

        $arr_friends = json_encode($friend->arrFriends,JSON_UNESCAPED_UNICODE);

        echo '$arr_friends = '.$arr_friends;

        $bot->update([
            'rating'=>$friend->rating,
            'arr_friends'=>$arr_friends
        ]);

    }

}


