<?php

namespace App\Http\Controllers;

class FirstController extends Controller{


    public function show($id){

        echo __METHOD__;

        echo $id;
    }
}