<?php

namespace App\Classes\Socket\Singletons;


use App\CONSTANT;
use App\Models\Person;

class SingleA{

    private $admin_conn;


    private static $instance = null;


    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function setAdmin($conn){

       $A = static::getInstance();

       $A->admin_conn = $conn;

    }



}