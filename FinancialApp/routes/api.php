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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/users','UsersController@store');
Route::post('/incomes','IncomeController@store');
Route::Get('/incomesshow/{id}','IncomeController@show');
Route::Patch('/incomesupdate/{id}','IncomeController@update');
Route::Delete('/incomesdestroy/{id}','IncomeController@destroy');
Route::post('/expenses','ExpenseController@store');
Route::Get('/expensesshow/{id}','ExpenseController@show');
Route::Patch('/expensesupdate/{id}','ExpenseController@update');
Route::Delete('/expensesdestroy/{id}','ExpenseController@destroy');



