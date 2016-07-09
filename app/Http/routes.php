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
    Route::any('/php', function () {return phpinfo();});

    // Image related

    Route::get('/public/images/{slug}', ['as' => 'AdminImageControllerShow', 'uses' => 'Admin\ImageController@show'])
        ->where(['slug' => '[a-z0-9%-]+']);
    Route::get('/public/images/{slug}.{ext}', ['as' => 'AdminImageControllerShowExt', 'uses' => 'Admin\ImageController@show'])
        ->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}']);
    Route::get('/public/images/{slug}/{w}-{h}.{ext}', ['as' => 'AdminImageControllerShowWidthHeightExt', function ($slug, $w, $h ,$ext) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, $ext, $w, $h);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'w' => '[0-9]+', 'h' => '[0-9]+']);
    Route::get('/public/images/{slug}/{w}-{h}', ['as' => 'AdminImageControllerShowWidthHeight', function ($slug, $w, $h) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, null, $w, $h);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'w' => '[0-9]+', 'h' => '[0-9]+']);
    Route::get('/public/images/{slug}/w-{w}', ['as' => 'AdminImageControllerShowWidth', function ($slug, $w) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, null, $w);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'w' => '[0-9]+']);
    Route::get('/public/images/{slug}/h-{h}', ['as' => 'AdminImageControllerShowHeight', function ($slug, $h) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, null, 0, $h);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'h' => '[0-9]+']);
    Route::get('/public/images/{slug}/w-{w}.{ext}', ['as' => 'AdminImageControllerShowWidthExt', function ($slug, $w, $ext) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, $ext, $w);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'w' => '[0-9]+']);
    Route::get('/public/images/{slug}/h-{h}.{ext}', ['as' => 'AdminImageControllerShowHeightExt', function ($slug, $h, $ext) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, $ext, 0, $h);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'h' => '[0-9]+']);
    Route::get('/public/images/{slug}/{mode}/{w}-{h}.{ext}', ['as' => 'AdminImageControllerShowModeWidthHeightExt', function ($slug, $mode, $w, $h, $ext) {
        return (new \App\Http\Controllers\Admin\ImageController)->show($slug, $ext, $w, $h, $mode);
    }])->where(['slug' => '[a-z0-9%-]+', 'ext' => '[a-z0-9]{1,4}', 'w' => '[0-9]+', 'h' => '[0-9]+', 'mode' => '[a-z]+']);
});

Route::group(['middleware' => ['web', 'auth', 'theme:backend'], 'prefix' => 'eanois'], function () {
    Route::any('/', 'AdminController@index');
    Route::get('settings', 'AdminController@viewSettings');
    Route::put('settings', 'AdminController@putSettings');
    Route::get('/themes', 'AdminController@themeIndex');
    Route::put('/themes', 'AdminController@themeUpdate');
    Route::resource('posts/categories', 'Admin\CategoryController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::resource('posts/tags', 'Admin\TagController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::resource('posts', 'Admin\PostController', ['except' => ['show']]);
    Route::resource('pages', 'Admin\PageController', ['except' => ['show']]);
    Route::post("posts/batch", 'Admin\PostController@bulkUpdate');
    Route::resource('images', 'Admin\ImageController', ['only' => ['index', 'store', 'destroy']]);
    Route::resource('links', 'Admin\LinkController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::get('frame/media', 'AdminController@mediaIframe');
});

Route::group(['middleware' => ['web', 'theme:backend'], 'prefix' => 'eanois'], function () {
    Route::auth();
});

Route::group(['prefix' => 'api'], function () {
    Route::any('slugify', 'APIController@getSlug');
});