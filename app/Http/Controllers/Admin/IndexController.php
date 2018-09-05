<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Auth\Access\Gate;
//use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Gate;

class IndexController extends AdminController
{
    //
    public function __construct(){

        parent::__construct();


       /* if (Gate::denies('VIEW_ADMIN')){
            abort(403);
        }*/


        $this->template = 'admin.index';

    }

   public function index(){

        $this->title = "Panel of Administrator";

        return $this->renderOutput();
    }

  /*  public function index()
    {
        return view('home');
    }*/

}
