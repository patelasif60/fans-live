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

Route::get('/news/{id}', 'NewsController@show')->name('frontend.news.detail');

Route::get('apple-app-site-association', 'HomeController@iosJson')->name('frontend.mobile.ios');

// Route::middleware(['auth', 'role:consumer'])->group(function () {
    Route::get('/select-seat/{clubId}/{matchId}', 'StadiumBlockController@pickBlock')->name('frontend.pick.block');
    Route::post('/blockseats', 'StadiumBlockController@getBlockSeat')->name('frontend.block.seat');
    Route::get('make-payment/{clubId}/{type}', 'PaymentController@selectCardAndMakePayment')->name('frontend.make.payment');
// });
