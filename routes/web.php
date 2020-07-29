<?php

use App\Mail\Notify_Manager;
use Illuminate\Support\Facades\Mail;

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
    return view('single_sign_on.login_sapido');
});
// Route::get('/single_sign_on_login', function () {
//     return view('single_sign_on.login');
// });
//single_sign_on_login
Route::get('/KingMaker', function () {
    return view('single_sign_on.login_sapido');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/KingMakerRegister',function(){
    return view('single_sign_on.register');
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::resource('/verify','VerifyController');
    Route::resource('/manager','ManagerController');
});


Route::get('/email', function () {

    // Mail::to('yutengchen0116@gmail.com')->send(new Notify_Manager());
    // return new Notify_Manager();
    Mail::to('yutengchen0116@gmail.com')->send(new Notify_Manager('789','456456456'));
    return new Notify_Manager('789','456456456');
});