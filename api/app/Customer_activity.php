<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer_activity extends Model
{
    protected $table = 'customer_activity';
    public $timestamps = false;
    protected $primaryKey = 'activity_id';

}
