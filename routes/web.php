<?php

use Illuminate\Support\Facades\Route;

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

// Main Routes
Route::get('/', 'HomeController@index')->name('home.index');

// Login Routes
Route::get('login', 'LoginController@index')->name('login.index');
Route::post('login', 'LoginController@attemptLogin')->name('login.attemptLogin');
Route::get('logout', 'LoginController@logout')->name('login.logout');

// Register Routes
Route::get('register', 'RegisterController@index')->name('register.index');
Route::post('register', 'RegisterController@store')->name('register.store');

// Verify Email Routes
Route::get('verify/{id}/{key}', 'VerifyEmailController@index')->name('verifyEmail.index');
Route::get('resend-email/{id}', 'VerifyEmailController@resendEmail')->name('verifyEmail.resendEmail');

// Channel Routes
Route::get('channel/create', 'ChannelController@create')->name('channel.create');
Route::post('channel/create', 'ChannelController@store')->name('channel.store');
Route::get('channel/{id}', 'ChannelController@show')->name('channel.show');
Route::delete('channel/{id}', 'ChannelController@destroy')->name('channel.destroy');
Route::get('channel/{id}/about', 'ChannelController@about')->name('channel.about');
Route::get('channel/{id}/settings', 'ChannelController@settings')->name('channel.settings');


// Role, Category and Country routes
Route::get('roles/add', 'HomeController@roles');
Route::get('countries/add', 'HomeController@countries');
Route::get('categories/add', 'HomeController@categories');
