<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class CoreResource extends Controller{


        public function index(){

            echo __METHOD__;
        }

        public function store(Request $request){

            print_r($_POST);
        }


}