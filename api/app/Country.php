<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    public $timestamps = false; 
    protected $primaryKey = 'country_id';

    protected $hidden = ['iso_code_2' , 'iso_code_3' , 'address_format' , 'postcode_required' , 'status'];
}
