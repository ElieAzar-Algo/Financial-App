<?php

namespace App\Http\Controllers;

use App\ProfitGoal;
use Exception;
use Illuminate\Http\Request;

class ProfitGoalController extends Controller
{
     public function create(Request $request){
        try{

        //TODO Sanitize and validate

        $name = $request["name"];
        $description = $request["description"];
        $target = $request["target"];
        $startDate = $request["start_date"];
        $endDate = $request["end_date"];

        ProfitGoal::create(array(
            'name' => $name,
            'description' => $description,
            'target' => $target,
            'status' => "ACTIVE",
            'start_date' => $startDate,
            'end_date' => $endDate
        ));

        return response()->json([
            'error' => false,
            'message' => "The Profit Goal has been added successfully"
        ],201);

    }catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured",
                'errormessage' => $errorInfo
            ],500);
        }
    }

    public function retrieve(){
        try{
            $profitGoals = ProfitGoal::paginate();
            return response()->json([
                'error'=>false,
                'ProfitGoals'=>$profitGoals
            ],200);
        }
        catch(\Illuminate\Database\QueryException $exception){
          $errorInfo = $exception->errorInfo;
          return response()->json([
              'error' => true,
              'message' => "Internal error occured"
          ],500);
        }
  
      }
      public function retrieveById($id){
         try{
            $profitGoal = ProfitGoal::where('id', '=', $id)->first();
             return response()->json([
                 'error'=>false,
                 'ProfitGoals'=>$profitGoal
             ],200);
         }
         catch(\Illuminate\Database\QueryException $exception){
           $errorInfo = $exception->errorInfo;
           return response()->json([
               'error' => true,
               'message' => "Internal error occured"
           ],500);
         }
   
       }

    public function update(Request $request,$id){
       try{
           $profitGoal = ProfitGoal::where('id', '=', $id)->first();
           $profitGoal->name = $request['name'];
           $profitGoal->description = $request['description'];
           $profitGoal->status = $request["status"];
           $profitGoal->target = $request["target"];
           $profitGoal->start_date = $request["start_date"];
           $profitGoal->end_date = $request["end_date"];
           $profitGoal->save(); 
           return response()->json([
            'error'=>false,
            'message'=>'The Profit Goal has been updated successfully',
            'ProfitGoal'=>$profitGoal
           ],200);
       }
      catch(\Illuminate\Database\QueryException $exception){
        $errorInfo = $exception->errorInfo;
        return response()->json([
            'error' => true,
            'message' => "Internal error occured"
        ],500);
       }
    }
    public function delete($id){
        try{
            $profitGoal = ProfitGoal::find($id);
            if (!$profitGoal) throw new Exception("The profit goal you are trying to delete does not exist");
            $cartegory->delete();
            return response()->json([
                'error'=>false,
                'message'=>'The profit goal has been deleted successfully'
               ],200);
        } catch(\Illuminate\Database\QueryException $exception){
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured"
            ],500);
           }
           catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ],500);
           }
    }
}


