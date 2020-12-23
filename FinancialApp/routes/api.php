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

//Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

Route::get('/userno', function (Request $request) {
    return "Not authenticated";
});


 Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', function (Request $request) {
        return "hi";
    });
   Route::post('/users','UsersController@store');
}); 



