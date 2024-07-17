<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// OAuth Routes
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider')->where('provider', 'twitter|facebook|google')->name('social.auth');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('provider', 'twitter|facebook|google');

Route::get('cmsuser/verification/{token}', 'Auth\RegisterController@setPassword')->name('cmsuser.verification');
Route::post('/passwordactivate', 'Auth\RegisterController@savePassword')->name('backend.cmsuser.password');
