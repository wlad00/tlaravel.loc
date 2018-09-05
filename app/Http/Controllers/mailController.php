<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class mailController extends Controller
{
    public function send()
    {
        Mail::send(['text'=>'mail'],['name','Sarthak'], function($message){

            $message->to('wladkor75@gmail.com','To Bitfumes')->subject('Test Email');
            $message->from('wladkor75@gmail.com','Biftumes');
        });
    }
}
