<?php

//namespace App;
namespace App\Vocabulary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DeFr extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'de_fr_done';

    protected $fillable = ['trend_1','trend_2','de','fr' ];

    public $timestamps = false;


}
