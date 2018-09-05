<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $p_rep;
    protected $a_rep;
    protected $user;
    protected $template;
    protected $content = FALSE;
    protected $title;
    protected $vars;

    public function __construct()
    {
        $this->user = Auth::user();

       /* if (!$this->user) {
            abort(403);
        }*/
    }

    public function renderOutput()
    {

        $this->vars = array_add($this->vars, 'title', $this->title);

       /* $menu = $this->getMenu();

        $navigation = view('admin.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);*/

        if($this->content){
            $this->vars = array_add($this->vars, 'content', $this->content);
        }

        $footer = view('admin.footer')->render();
        $this->vars = array_add($this->vars,'footer',$footer);

        return view($this->template)->with($this->vars);
    }

   /* public function getMenu(){

        return Menu::make('adminMenu',function($menu){

            $menu->add('Statji',array('route'=>'admin.articles.index'));
            $menu->add('Menu',array('route'=>'admin.articles.index'));
            $menu->add('Poljzovateli',array('route'=>'admin.articles.index'));
            $menu->add('Privilegii',array('route'=>'admin.articles.index'));
        });
    }*/

}
