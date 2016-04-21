<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/practice', function() {

    echo 'Hello World!<br>';
    echo App::environment();

    $data = Array('foo' => 'bar');
    Debugbar::info($data);
    Debugbar::error('Error!');
    Debugbar::warning('Watch out…');
    Debugbar::addMessage('Another message', 'mylabel');

    return 'Practice';

});

Route::get('/books', 'BookController@getIndex');
Route::get('/books/create', 'BookController@getCreate');
Route::post('/books/create', 'BookController@postCreate');
Route::get('/books/show/{title?}', 'BookController@getShow');
Route::get('/books/{category}', function($category) {
        return 'Here are all the books in the category of '.$category;
});

Route::get('/', function () {
    return view('welcome');
});
