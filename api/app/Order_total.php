<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_total extends Model
{
    protected $table = 'order_total';
    public $timestamps = false;
    protected $primaryKey = 'order_total_id';
}
