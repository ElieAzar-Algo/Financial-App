<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Category;

class CategoryController extends Controller
{
    public function create(Request $request){
     
        try{
        $name = $request['name'];
        Category::create(array(
            'name' => $name
        ));

        return response()->json([
            'error' => false,
            'message' => "The category has been added successfully"
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

    public function retrieve(Request $request){
      try{
          $categories = Category::paginate();
          return response()->json([
              'error'=>false,
              'categories'=>$categories
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
           $cartegory = Category::where('id', '=', $id)->first();
           $cartegory->name = $request['name'];
           $cartegory->save(); 
           return response()->json([
            'error'=>false,
            'message'=>'The category has been updated successfully',
            'category'=>$cartegory
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
            $cartegory = Category::find($id);
            if (!$cartegory) throw new Exception("The Category you are trying to delete does not exist");
            $cartegory->delete();
            return response()->json([
                'error'=>false,
                'message'=>'The category has been deleted successfully'
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
