<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;// copy form user.php


class Expense extends Model
{
    use Notifiable;
protected $table="expenses";
protected $fillable = [
    "id ",	"name", 	"description" ,	"amount" ,	"date" ,	"category_id", 	"type_id" ,	"start_date" ,	"end_date" 
];

}
