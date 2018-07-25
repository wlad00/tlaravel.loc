<?php

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

Route::get('/admin', 'AdminController@index')->name('admin');


// admin/edit/post
/*Route::group(['prefix'=>'admin', 'middleware'=>['web','auth']],function(){

    Route::get('/',['uses'=>'Admin\AdminController@show', 'as'=>'admin_index']);
    Route::get('/add/post',['uses'=>'Admin\AdminPostController@create', 'as'=>'admin_add_post']);
});*/