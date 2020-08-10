<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_history extends Model
{
    protected $table = 'order_history';
    public $timestamps = false;
    protected $primaryKey = 'order_history_id';
}
