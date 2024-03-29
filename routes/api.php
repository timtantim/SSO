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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register','Api\AuthController@register');
Route::post('/updatepass','Api\AuthController@updatepass');
Route::post('/loadchildwebsite','Api\AuthController@loadchildwebsite');
Route::post('/loadfactory','Api\AuthController@loadfactory');

Route::post('/verifyuser','Api\VerifyApiController@verifyuser');
Route::post('/setauthuser','Api\ManagerApiController@setauthuser');



Route::middleware('cors')->post('/login','Api\AuthController@login');
// Route::post('/login','Api\AuthController@login');
Route::middleware('auth:api')->post('/logout','Api\AuthController@logout');
Route::middleware('auth:api')->post('/checkUser','Api\AuthController@checkUser');





