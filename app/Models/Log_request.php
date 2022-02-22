<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log_request extends Model

{

    protected $table = 'log_request';

    protected $fillable = [
        'request','response','path','time','hash','ip','size','size_resp','brief','time_now','path_full'
    ];

    public $timestamps = false;


   /* public function setCreatedAtAttribute() {
        $this->attributes['created_at'] = \Carbon\Carbon::now();
    }*/


}


