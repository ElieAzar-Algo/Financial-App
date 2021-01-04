<?php

namespace App\Http\Controllers;

use App\Income;
use App\Expense;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{
     public function getIncomeData($from,$to){

     // Returns: 
     //  1- sum 
     //  2- the percentage of each category
      
     // Note: the end date is the last - id the due date of the last income/payment

     //TODO sanitize the data
        $fixed = Imcome::
        select('amount','categories.name As category','amount','date')
        ->whereNotNull("date")
        ->where("date",">=",$from)
        ->where("date","<=",$to)
        ->join('categories', 'categories.id', '=', 'incomes.category_id')
        ->get();
        
        $recurring = 
        Income::
        select('amount','categories.name As category','amount','date')
        ->whereNull("date") //the date should not be fixed
        
        //in case the recurring started within the period that we are generating period to and stops recurring within this period
        // [ --- ]
        // or it started before the period and ends within it 
        // ---[--- ]
        //or started within the period and ends after after it
        // [ ---]---  
        ->where(
             function($q){
               $q->where($from,">=","start_date")
               ->where($from,"<=","end_date")
             }
        )
        ->orWhere(
             function($q){
               $q->where($to,">=","start_date")
               ->where("end_date",">",$to)
             }
        )
        
        // in case we are generating a report of a period: we want to fetch the reccuring incomes that started before this period and last to after of this period
        // ---[---]---

        ->orWhere(
          function($q){
            $q->where("start_date","<",$from)
            ->where("end_date",">",$to)
          })
        ->join('categories', 'categories.id', '=', 'incomes.category_id')
        ->get();
        
        var_dump($fixed);
        var_dump($recurring);

      /*   foreach ($fixed as $f) {
          echo $f->name;
      } 

$q->where(function ($query) {
    $query->where('gender', 'Male')
        ->where('age', '>=', 18);
})->orWhere(function($query) {
    $query->where('gender', 'Female')
        ->where('age', '>=', 65);	
        */
})



$date->diffInWeeks($otherDate);
        $date->startOfWeek(); // 2016-10-17 00:00:00.000000
        $date->endOfWeek(); // 2016-10-23 23:59:59.000000

        $updated->diffInMonths($now)
        //if we are reporting the second week 

        //https://stackoverflow.com/questions/976669/how-to-check-if-a-date-is-in-a-given-range
        /**
         * SELECT title, category.name, amount, (amount*100/ (select SUM(amount) from income)) AS percent FROM `income` join category ON category.id = income.category_id GROUP BY income.id 
         */
     }

}


