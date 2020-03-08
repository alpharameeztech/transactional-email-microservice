<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'        => 'v1',
], function () {

    Route::post('/send/email', 'Api\EmailController@send')->name('send.email');

    Route::post('/register', 'Api\RegisterController@register')->name('user.register');

    Route::post('/forgot/password', 'Api\ForgotPasswordController@getResetToken')->name('forgot.password');

    Route::post('/password/reset', 'Api\ResetPasswordController@reset')->name('reset.password');

});
