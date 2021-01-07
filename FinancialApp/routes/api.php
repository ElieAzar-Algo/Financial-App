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

    Route::post('/incomes','IncomeController@store');
    Route::Get('/incomesshow/{id}','IncomeController@show');
    Route::Patch('/incomesupdate/{id}','IncomeController@update');
    Route::Delete('/incomesdestroy/{id}','IncomeController@destroy');
    Route::post('/expenses','ExpenseController@store');
    Route::Get('/expensesshow/{id}','ExpenseController@show');
    Route::Patch('/expensesupdate/{id}','ExpenseController@update');
    Route::Delete('/expensesdestroy/{id}','ExpenseController@destroy');
}); 