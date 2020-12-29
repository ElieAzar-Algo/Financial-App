<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfitGoal extends Model
{
    protected $table = "profits_goals";

    protected $fillable = [
        'name','description','end_date','start_date','status','target'
    ];
}

