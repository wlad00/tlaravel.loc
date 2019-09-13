<?php

//namespace App;
namespace App\Vocabulary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EsFr extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'es_fr';

    protected $fillable = ['trend_1','trend_2','es','fr' ];

    public $timestamps = false;


}
