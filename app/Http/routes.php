<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web', 'theme:frontend']], function () {
    Route::any('/', "FrontEndController@index");
    Route::get('/file', function (){
        return '<form action="/file" method="post" enctype="multipart/form-data">'.csrf_field().'<input name="file" type="file"/><input type="submit"/></form>';
    });
    Route::post('/file', function (){
        dump(Request::file('file')->move('/Users/blueset/htdocs/n1laravel/storage/app/public'));
    });
});

Route::group(['middleware' => ['web', 'theme:backend'], 'prefix' => 'eanois'], function () {
    Route::auth();
    Route::any('/', 'AdminController@index');
    Route::get('settings', 'AdminController@viewSettings');
    Route::post('settings', 'AdminController@putSettings');
    Route::resource('posts/categories', 'Admin\CategoryController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::resource('posts/tags', 'Admin\TagController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::resource('posts', 'Admin\PostController', ['except' => ['show']]);
    Route::resource('images', 'Admin\ImageController', ['except' => ['show']]);
});

Route::group(['prefix' => 'api'], function () {
    Route::any('slugify', 'APIController@getSlug');
});