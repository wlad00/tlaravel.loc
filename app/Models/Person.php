<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model

{

    protected $table = 'persons';

    protected $fillable = ['name','email','rating','arr_friends'];

    public $timestamps = false;


    public static function updateBot($friend){

        if(!is_numeric($friend->email))
                                    return;

        /*-----------------------------------*/

        $bot = static::where('email', $friend->email)->first();

        $bot->update([
            'rating'=>$friend->rating,
            'arr_friends'=>json_encode($friend->arrFriends,JSON_UNESCAPED_UNICODE)
        ]);

    }

}


