<?php

//namespace App;
namespace App\Vocabulary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EsDe extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'es_de';

    protected $fillable = ['trend_1','trend_2','es','de' ];

    public $timestamps = false;


}
