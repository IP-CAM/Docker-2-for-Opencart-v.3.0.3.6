<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock_status extends Model
{
    protected $table = "stock_status";
    public $timestamps = false;
    protected $primaryKey = 'stock_status_id';

    protected $hidden = ['stock_status_id' , 'language_id'];
}
