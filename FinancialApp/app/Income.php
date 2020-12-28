<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;// copy form user.php

class Income extends Model
{
    use Notifiable;
protected $table="incomes";
protected $fillable = [
    "id ",	"name", 	"description" ,	"amount",	"created_at" ,	"updated_at" ,	"date" ,	"category_id", 	"type_id" ,	"start_date" ,	"end_date" 
];

}
