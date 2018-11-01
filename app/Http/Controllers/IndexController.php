<?php

namespace App\Http\Controllers;



class IndexController extends Controller
{
    //
    public function __construct(){

//        parent::__construct();


    }

    public function index($lang = null){

//            \App::setLocale($lang);

        return view('welcome');
    }

    /*  public function index()
      {
          return view('home');
      }*/

}