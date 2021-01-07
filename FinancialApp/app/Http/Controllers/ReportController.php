<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Income;
use App\Expense;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
  public function getIncomeData(Request $request){
    
      $amount_per_category = [];
      $recurringData = [];
      $fixedData = [];
      $sum = 0;
      $from = date($request->startdate);
      $to = date($request->enddate);
      
      // Returns: 
      //   1- sum 
      //   2- the percentage of each category
      
      // Note: the end date is the last - id the due date of the last income/payment
      //TODO sanitize the data

      DB::enableQueryLog();
      $fixed = Income::
      select('incomes.name','description','amount','categories.name As category','amount','date','types.name as type')
      ->whereNotNull("date")
      ->where("date",">=",$from)
      ->where("date","<=",$to)
      ->join('categories', 'categories.id', '=', 'incomes.category_id')
      ->join('types', 'types.id', '=', 'incomes.type_id')
      ->get(); 

      foreach($fixed as $item){
          $this->addAmountToCategory($item->category,$item->amount,$amount_per_category);
          $sum += $item->amount;

          $filtered = array(
            "name" => $item->name,
            "description" => $item->description,
            "type"=>$item->type,
            "category"=>$item->category,
            "amount"=>$item->amount,
            "date"=>$item->date
          );

          array_push($fixedData, $filtered);
      }
      

      $recurring = 
      Income::
      select('incomes.name','description','amount','categories.name As category','amount','start_date','end_date', 'types.name as type')
      ->whereNull("date") //the date should not be fixed
      
      //in case the recurring started within the period that we are generating period to and stops recurring within this period
      // [ --- ]
      // or it started before the period and ends within it 
      // ---[--- ]
      //or started within the period and ends after after it
      // [ ---]---  
      ->where(
        function($q) use($from){
          $q->where("start_date","<=","$from")
          ->where("end_date",">=","$from");
        }
        )
        ->orWhere(
          function($q) use($to){
            $q->where("start_date","<=","$to")
            ->where("end_date",">=","$to");
          }
          )
          
          // in case we are generating a report of a period: we want to fetch the reccuring incomes that started before this period and last to after of this period
          // ---[---]---
          
          ->orWhere(
            function($q) use($to,$from){
              $q->where("start_date","<",$from)
              ->where("end_date",">",$to);
            })
            ->join('categories', 'categories.id', '=', 'incomes.category_id')
            ->join('types', 'types.id', '=', 'incomes.type_id')
            ->get();
            

        
        foreach ($recurring as $item) {
          $amount = 0;
          

          $startDate =  new Carbon($item->start_date);
          $endDate = new Carbon($to);
          $date1 = new Carbon($from);   
          
          //$numbOfLastOccurence = 0;
          // $date2 = new Carbon($item->start_date);   
          //compare the the recurred transaction start date with the start of the period
          // if period start date is > transction start date then calculate the the nearest last payment 
          // if transction start date > period start date do nothing
          // while not > end  date calculte the next payment due date 
/* 
          if($date1->gt($date2)){
              // calculate the the nearest last and occuring date 
              if($item->type == "weekly"){
                $numbOfLastOccurence = $date2->diffInWeeks($date1)-1; 
              //  echo "---$numbOfLastOccurence---";
                
                $startDate = $date2->addWeeks($numbOfLastOccurence);
              }
              
              if($item->type == "monthly"){
                $numbOfLastOccurence = $date2->diffInMonths($date1)-1; 
                //echo "---$numbOfLastOccurence---";

                $startDate = $date2->addMonths($numbOfLastOccurence);
              }
              
              if($item->type == "yearly"){
                $numbOfLastOccurence = $date2->diffInYears($date1)-1; 
               // echo "---$numbOfLastOccurence---";

                $startDate = $date2->addYears($numbOfLastOccurence);
              }
          }
           */

          $nextPayment = $startDate->copy();
          $dueDates = [];
          
          //TODO the loop should start from the nearest last due date from the start date perspective not from recurring start date
          while($nextPayment->lte($endDate)){
            if($nextPayment->gte($date1)){
              array_push($dueDates,$nextPayment->format('Y-m-d'));
            }
            if($item->type == "weekly"){
              $nextPayment = $nextPayment->addWeek();
            }
            
            if($item->type == "monthly"){
              $nextPayment = $nextPayment->addMonth();
            }
            
            if($item->type == "yearly"){
              $nextPayment = $nextPayment->addYear();
            }
            
          }

          if(count($dueDates) == 0){
            continue;
          }

          $amount  =  count($dueDates)*$item->amount;
          $sum += $amount;
          
          $this->addAmountToCategory($item->category,$amount,$amount_per_category);
          
          $filtered = array(
            "name" => $item->name,
            "description" => $item->description,
            "type"=>$item->type,
            "category"=>$item->category,
            "amount"=>$amount,
            "due_dates"=>$dueDates,
            "start_date"=>$item->start_date,
            "end_date"=>$item->end_date
          );
          array_push($recurringData, $filtered);
        } 
        
        $categories_percentage =$this->getCategoryPercentages($sum,$amount_per_category);
        return response()->json([
        'error'=>false,
        'message'=>'The income report has been retrieved successfully',
        'fixed_incomes' => $fixedData,
        'recurring_incomes'=>$recurringData,
        'total_sum' => $sum,
        'amount_per_category' => $amount_per_category,
        'categories_percentage' => $categories_percentage
       ],200);
     }


     public function getExpenseData(Request $request){
    
      $amount_per_category = [];
      $recurringData = [];
      $fixedData = [];
      $sum = 0;
      $from = date($request->startdate);
      $to = date($request->enddate);
      
      // Returns: 
      //   1- sum 
      //   2- the percentage of each category
      
      // Note: the end date is the last - id the due date of the last income/payment
      //TODO sanitize the data

      $fixed = Expense::
      select('expenses.name','description','amount','categories.name As category','amount','date','types.name as type')
      ->whereNotNull("date")
      ->where("date",">=",$from)
      ->where("date","<=",$to)
      ->join('categories', 'categories.id', '=', 'expenses.category_id')
      ->join('types', 'types.id', '=', 'expenses.type_id')
      ->get(); 

      foreach($fixed as $item){
          $this->addAmountToCategory($item->category,$item->amount,$amount_per_category);
          $sum += $item->amount;

          $filtered = array(
            "name" => $item->name,
            "description" => $item->description,
            "type"=>$item->type,
            "category"=>$item->category,
            "amount"=>$item->amount,
            "date"=>$item->date
          );

          array_push($fixedData, $filtered);
      }
      

      $recurring = 
      Expense::
      select('expenses.name','description','amount','categories.name As category','amount','start_date','end_date', 'types.name as type')
      ->whereNull("date") //the date should not be fixed
      
      //in case the recurring started within the period that we are generating period to and stops recurring within this period
      // [ --- ]
      // or it started before the period and ends within it 
      // ---[--- ]
      //or started within the period and ends after after it
      // [ ---]---  
      ->where(
        function($q) use($from){
          $q->where("start_date","<=","$from")
          ->where("end_date",">=","$from");
        }
        )
        ->orWhere(
          function($q) use($to){
            $q->where("start_date","<=","$to")
            ->where("end_date",">=","$to");
          }
          )
          
          // in case we are generating a report of a period: we want to fetch the reccuring incomes that started before this period and last to after of this period
          // ---[---]---
          
          ->orWhere(
            function($q) use($to,$from){
              $q->where("start_date","<",$from)
              ->where("end_date",">",$to);
            })
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->join('types', 'types.id', '=', 'expenses.type_id')
            ->get();
            

        
        foreach ($recurring as $item) {
          $amount = 0;
          

          $startDate =  new Carbon($item->start_date);
          $endDate = new Carbon($to);
          $date1 = new Carbon($from);   
          
          //$numbOfLastOccurence = 0;
          // $date2 = new Carbon($item->start_date);   
          //compare the the recurred transaction start date with the start of the period
          // if period start date is > transction start date then calculate the the nearest last payment 
          // if transction start date > period start date do nothing
          // while not > end  date calculte the next payment due date 
/* 
          if($date1->gt($date2)){
              // calculate the the nearest last and occuring date 
              if($item->type == "weekly"){
                $numbOfLastOccurence = $date2->diffInWeeks($date1)-1; 
              //  echo "---$numbOfLastOccurence---";
                
                $startDate = $date2->addWeeks($numbOfLastOccurence);
              }
              
              if($item->type == "monthly"){
                $numbOfLastOccurence = $date2->diffInMonths($date1)-1; 
                //echo "---$numbOfLastOccurence---";

                $startDate = $date2->addMonths($numbOfLastOccurence);
              }
              
              if($item->type == "yearly"){
                $numbOfLastOccurence = $date2->diffInYears($date1)-1; 
               // echo "---$numbOfLastOccurence---";

                $startDate = $date2->addYears($numbOfLastOccurence);
              }
          }
           */

          $nextPayment = $startDate->copy();
          $dueDates = [];
          
          while($nextPayment->lte($endDate)){
            if($nextPayment->gte($date1)){
              array_push($dueDates,$nextPayment->format('Y-m-d'));
            }
            if($item->type == "weekly"){
              $nextPayment = $nextPayment->addWeek();
            }
            
            if($item->type == "monthly"){
              $nextPayment = $nextPayment->addMonth();
            }
            
            if($item->type == "yearly"){
              $nextPayment = $nextPayment->addYear();
            }
          }
          
          if(count($dueDates) == 0){
            continue;
          }
          $amount  =  count($dueDates)*$item->amount;
          $sum += $amount;
          $this->addAmountToCategory($item->category,$amount,$amount_per_category);
          
          $filtered = array(
            "name" => $item->name,
            "description" => $item->description,
            "type"=>$item->type,
            "category"=>$item->category,
            "amount"=>$amount,
            "due_dates"=>$dueDates,
            "start_date"=>$item->start_date,
            "end_date"=>$item->end_date
          );
          array_push($recurringData, $filtered);
        } 
        
        $categories_percentage =$this->getCategoryPercentages($sum,$amount_per_category);
        return response()->json([
        'error'=>false,
        'message'=>'The expenses report has been retrieved successfully',
        'fixed_expenses' => $fixedData,
        'recurring_expenses'=>$recurringData,
        'total_sum' => $sum,
        'amount_per_category' => $amount_per_category,
        'categories_percentage' => $categories_percentage
       ],200);
     }

     protected function addAmountToCategory($category,$amount,&$amount_per_category){
      if (array_key_exists($category,$amount_per_category)) {
        $amount_per_category[$category] += $amount;
       }
       else{
         $amount_per_category[$category]=$amount;
       }
     }

     protected function getCategoryPercentages($total,$array){
      $arr = $array;

      foreach($arr as $key => $val){
          $arr[$key]= $this->percentage($val,$total);
       }
       return $arr;
     }

     protected function percentage($part,$total){
        return $part*100/$total;
     }

}


