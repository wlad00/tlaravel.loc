<?php

//namespace App;
namespace App\Vocabulary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Spanish extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'spanish';

    protected $fillable = ['en','ru','es','de','fr' ];

    public $timestamps = false;


}