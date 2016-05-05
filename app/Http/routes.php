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


/* Main display routes */

Route::get('/', 'BookController@getIndex');
Route::get('/books', 'BookController@getIndex');
Route::get('/books/show/{title?}', 'BookController@getShow');
// Route::get('/books/{category}', function($category) {
//        return 'Here are all the books in the category of '.$category;
// });


/* Restricting multiple routes with Middleware */

Route::get('/', 'WelcomeController@getIndex');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/books/create', 'BookController@getCreate');
    Route::post('/books/create', 'BookController@postCreate');
    Route::get('/books/edit/{id?}', 'BookController@getEdit');
    Route::post('/books/edit', 'BookController@postEdit');
});
	#	Route::get('/books/create', 'BookController@getCreate');
	#	Route::get('/books/create', [
	#		'middleware' => 'auth',
	#		'uses' => 'BookController@getCreate'
	#	]);
	#	Route::post('/books/create', 'BookController@postCreate');
	#	Route::get('/books/edit/{id?}', 'BookController@getEdit');
	#	Route::post('/books/edit', 'BookController@postEdit');


/* Check Login status */
Route::get('/show-login-status', function() {

    # You may access the authenticated user via the Auth facade
    $user = Auth::user();

    if($user) {
        echo 'You are logged in.';
        dump($user->toArray());
    } else {
        echo 'You are not logged in.';
    }

    return;
});



/* Routes in relation to register, login and logout */

# Process login
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');

# Process logout
Route::get('/logout', 'Auth\AuthController@logout');
Route::get('/logout/confirm', function(){
	echo 'You have been logged out';
});

# Process Registration
Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');



/* Routes in relation to PracticeController */

for($i = 0; $i <= 100; $i++) {
    Route::get("/practice/ex".$i, "PracticeController@getEx".$i);
}


/* Debug Route */

Route::get('/debug', function() {

    echo '<pre>';	

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(config('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    /*
    The following line will output your MySQL credentials.
    Uncomment it only if you're having a hard time connecting to the database and you
    need to confirm your credentials.
    When you're done debugging, comment it back out so you don't accidentally leave it
    running on your live server, making your credentials public.
    */
    //print_r(config('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    }
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';
});

/* Practice Routes */

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

/* Routes for sending emails */

# Just like the debug route we set up when testing DB credentials,
# be sure this code does not end up on a live, production server
# as it reveals sensitive information.
Route::get('/debug-email', function() {
    dump(Config::get('mail'));
});

/* Routes for deleting books */
Route::get('/books/confirm-delete/{id?}', 'BookController@getConfirmDelete');
Route::get('/books/delete/{id?}', 'BookController@getDoDelete');