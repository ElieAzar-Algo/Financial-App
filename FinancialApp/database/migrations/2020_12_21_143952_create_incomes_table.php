<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->float('amount',8,2);
            $table->timestamps();
            $table->date('date');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->foreignId('type_id')->references('id')->on('types');
            $table->date('start_date');
            $table->date('end_date');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
