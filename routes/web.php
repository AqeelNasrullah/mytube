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
Route::get('search', 'HomeController@search')->name('home.search');

Route::get('trending', 'HomeController@trending')->name('home.trending');
Route::get('subscriptions', 'HomeController@subscriptions')->name('home.subscriptions');
Route::get('history', 'HomeController@history')->name('home.history');

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
Route::get('channel/{id}/edit', 'ChannelController@edit')->name('channel.edit');
Route::put('channel/{id}/edit', 'ChannelController@update')->name('channel.update');
Route::delete('channel/{id}', 'ChannelController@destroy')->name('channel.destroy');
Route::get('channel/{id}/about', 'ChannelController@about')->name('channel.about');
Route::get('channel/{id}/settings', 'ChannelController@settings')->name('channel.settings');

Route::get('channel/subscribe', function () {
    return redirect()->route('home.index');
});
Route::post('channel/subscribe', 'ChannelController@subscribe')->name('channel.subscribe');

// Video Routes
Route::get('video/upload', 'VideoController@create')->name('video.create');
Route::get('video/uploaded', function () {
    return redirect()->route('video.create');
});
Route::post('video/uploaded', 'VideoController@uploadVideo')->name('video.uploadVideo');
Route::post('video/upload', 'VideoController@store')->name('video.store');
Route::get('video/{video:slug}', 'VideoController@show')->name('video.show');
Route::get('video/{video:slug}/edit', 'VideoController@edit')->name('video.edit');
Route::get('video/updated', function () {
    return redirect()->route('home.index');
});
Route::post('video/updated', 'VideoController@updateVideo')->name('video.updateVideo');
Route::put('video/{video:slug}/edit', 'VideoController@update')->name('video.update');
Route::delete('video/{video:slug}', 'VideoController@destroy')->name('video.destroy');

// Comments Routes
Route::get('comments/create', function () {
    return redirect()->route('home.index');
});
Route::post('comments/create', 'CommentController@store')->name('comment.store');
Route::get('comments/delete', function () {
    return redirect()->route('home.index');
});
Route::post('comments/delete', 'CommentController@destroy')->name('comment.destroy');

// Replies Routes
Route::get('replies/create', function () {
    return redirect()->route('home.index');
});
Route::post('replies/create', 'ReplyController@store')->name('reply.store');
Route::get('replies/delete', function () {
    return redirect()->route('home.index');
});
Route::post('replies/delete', 'ReplyController@destroy')->name('reply.destroy');

// Likes Routes
Route::get('videos/like', function () {
    return redirect()->route('home.index');
});
Route::post('videos/like', 'LikeController@store')->name('like.store');
Route::get('comments/like', function () {
    return redirect()->route('home.index');
});
Route::post('comments/like', 'LikeController@likeComment')->name('like.likeComment');
Route::get('replies/like', function () {
    return redirect()->route('home.index');
});
Route::post('replies/like', 'LikeController@likeReply')->name('like.likeReply');

// Dislikes Routes
Route::get('videos/dislike', function () {
    return redirect()->route('home.index');
});
Route::post('videos/dislike', 'DislikeController@store')->name('dislike.store');
Route::get('comments/dislike', function () {
    return redirect()->route('home.index');
});
Route::post('comments/dislike', 'DislikeController@dislikeComment')->name('dislike.dislikeComment');
Route::get('replies/dislike', function () {
    return redirect()->route('home.index');
});
Route::post('replies/dislike', 'DislikeController@dislikeReply')->name('dislike.dislikeReply');

// Role, Category and Country routes
Route::get('guest/add', 'HomeController@guest');
Route::get('roles/add', 'HomeController@roles');
Route::get('countries/add', 'HomeController@countries');
Route::get('categories/add', 'HomeController@categories');
