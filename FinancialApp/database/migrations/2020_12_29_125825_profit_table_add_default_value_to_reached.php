<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfitTableAddDefaultValueToReached extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profits_goals', function (Blueprint $table) {
            $table->float('reached',8,2)->default('0')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profits_goals', function (Blueprint $table) {
            //
        });
    }
}
