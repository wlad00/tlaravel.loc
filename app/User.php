<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles(){
        return $this->hasMany('App\Article');
    }

    public function roles(){
        return $this->belongsToMany('App\Role','role_user');
    }

    public function canDo($permission, $require = FALSE){

        if(is_array($permission)){
            dump($permission);
        }
        else {
            foreach ($this->roles as $role){
                foreach ($role->perms as $perm){

                    if(str_is($permission, $perm->name)){
                        return TRUE;
                    }
                }
            }
        }
    }

}
