<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::group([

    'middleware' => 'jwt.verify',

], function ($router) {

    Route::post('logout', 'ApiController@logout');
    Route::post('resend-otp', 'ApiController@resendOtp');
    Route::post('forgot-password', 'ApiController@forgotPassword');
    Route::post('verify-email', 'ApiController@verifyEmail');
    Route::post('add-product', 'ApiController@forgotPassword');

});



