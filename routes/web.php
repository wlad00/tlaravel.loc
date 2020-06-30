<?php

//store a push subscriber.
Route::post('/push','PushController@store');

//make a push notification.
Route::get('/push','PushController@push')->name('push');
//Route::get('/push','PushController@push')->name('push');




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

Route::get('/',
    [function () { return view('welcome');},
    'as'=>'root']
);

Route::get('/about/{id}','FirstController@show');


//Route::get('/articles',
//    ['uses'=>'Admin\Core@getArticles', 'as'=>'articles']);
//Route::get('/article/{id}',
//    ['uses'=>'Admin\Core@getArticle', 'as'=>'article']);

//list pages
//Route::resource('/pages','Admin\CoreResource');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//
//Route::get('/admin', 'AdminController@index')->name('admin');

// admin/edit/post
/*Route::group(['prefix'=>'admin', 'middleware'=>['web','auth']],function(){

    Route::get('/',['uses'=>'Admin\AdminController@show', 'as'=>'admin_index']);
    Route::get('/add/post',['uses'=>'Admin\AdminPostController@create', 'as'=>'admin_add_post']);
});*/

Route::get('send', 'mailController@send');

//admin
Route::group(['middleware'=>'auth','prefix'=>'admin'],function(){

    //admin
    Route::get('/',['uses'=>'Admin\IndexController@index','as'=> 'adminIndex']);

    Route::resource('/articles','Admin\ArticlesController');
});

//Route::get('/admin',['uses'=>'Admin\IndexController@index','as'=> 'adminIndex']);

//Route::get('/admin',['uses'=>'Admin\IndexController@index','as'=> 'adminIndex']);

Route::get('subs', function(){


    if (Gate::allows('subs-only', Auth::user())) {

            return view('subs');
    }
    else{
        return 'You are not a subscriber';
    }
});




























