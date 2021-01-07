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
    $date1 = new Carbon\Carbon("2021-5-6");
    $date2 = $date1->addWeek();
    return $date2;
});
//Route::post('/users','UsersController@store');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', function (Request $request) {
     
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
    Route::get('/goal/{id}','ProfitGoalController@retreiveById');
    Route::put('/goal/{id}','ProfitGoalController@update');
    Route::delete('/goal/{id}','ProfitGoalController@delete');


    //Income Reports
    Route::get("/report/income",'ReportController@getIncomeData');
    Route::get("/report/expense",'ReportController@getExpenseData');

    Route::get('/incomes','IncomeController@index');
    Route::post('/income','IncomeController@store');
    Route::Get('/income/{id}','IncomeController@show');
    Route::Patch('/income/{id}','IncomeController@update');
    Route::Delete('/income/{id}','IncomeController@destroy');

    Route::get('/expenses','ExpenseController@index');
    Route::post('/expense','ExpenseController@store');
    Route::Get('/expense/{id}','ExpenseController@show');
    Route::Patch('/expense/{id}','ExpenseController@update');
    Route::Delete('/expense/{id}','ExpenseController@destroy');

}); 


