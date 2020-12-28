<?php

namespace App\Http\Controllers;
use App\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses=Expense::all();
        return response()-> json([
            'status'=>200,
            'expense'=> $expenses
        ]);

        }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data=$request->all();


        $expense= new Expense();
        $expense->fill($data);

       
       
        $expense->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expenses $expense   
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   $expenses=new Expense();
        return  $expenses::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $expense = Expense::where('id', $id)->first();
        $expense->update($data);

        return response()->json([
            'status' => 200,
            'expense'  => $expense
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\expense $expense 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expense::where('id', $id)->delete();
    }
}
