<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer_ip extends Model
{
    //
    protected $table = 'customer_ip';
    public $timestamps = false;
    protected $primaryKey = 'customer_ip_id';
    
}
