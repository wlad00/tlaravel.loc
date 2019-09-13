<?php

//namespace App;
namespace App\Vocabulary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RuFr extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ru_fr';

    protected $fillable = ['trend_1','trend_2','ru','fr' ];

    public $timestamps = false;


}
