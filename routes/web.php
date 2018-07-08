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

Route::get('/', function () {
    App::setLocale('fr');

    return view('welcome');
});

Route::get('/{lang}', function () {
    App::setLocale('{lang}');

    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::match(['get', 'post'], '/botman/{lang}', 'BotManController@handle');
//Route::get('/botman/tinker', 'BotManController@tinker');
