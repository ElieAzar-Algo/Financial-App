<?php

namespace App\Http\Controllers;
use App\Income;

use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes=Income::all();
        return response()-> json([
            'status'=>200,
            'income'=> $incomes
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


        $income= new income();
        $income->fill($data);
        $income->save();
        return response()->json([
            'income'=>$income
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $income=new Income();
        return  $income::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\income $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $income = Income::where('id', $id)->first();
        $income->update($data);

        return response()->json([
            'status' => 200,
            'income'  => $income
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\income $income 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Income::where('id', $id)->delete();
    }
}



