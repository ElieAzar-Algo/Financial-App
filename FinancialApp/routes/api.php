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

Route::post('/register', 'AuthController@register');
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
    Route::get('/user/{id}','UsersController@show');
    Route::get('/users','UsersController@index');

    //Category Routes
    Route::post('/category','CategoryController@create');
    Route::get('/category','CategoryController@retrieve');
    Route::put('/category/{id}','CategoryController@update');
    Route::delete('/category/{id}','CategoryController@delete');

    //Profit Goal
    Route::post('/goal','ProfitGoalController@create');
    Route::get('/goal','ProfitGoalController@retrieve');
    Route::get('/goal/{id}','ProfitGoalController@retrieveById');
    Route::put('/goal/{id}','ProfitGoalController@update');
    Route::delete('/goal/{id}','ProfitGoalController@delete');
}); 



