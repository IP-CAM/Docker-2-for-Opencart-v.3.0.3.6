<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currency';
    public $timestamps = false;
    protected $primaryKey = 'currency_id';
    
}
