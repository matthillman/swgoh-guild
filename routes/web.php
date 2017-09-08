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
    return view('welcome');
});

Route::resource('guild', '\App\Http\Controllers\GuildController');

Route::group(['middleware' => 'cacheable'], function () {
    Route::get('guild/{id}/members', '\App\Http\Controllers\GuildController@members');
    Route::get('guild/{id}/characters', '\App\Http\Controllers\GuildController@characters');
 });