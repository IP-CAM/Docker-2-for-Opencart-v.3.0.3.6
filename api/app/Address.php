<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Country;
use App\Zone;

class Address extends Model
{
    protected $table = 'address';
    public $timestamps = false;
    protected $primaryKey = 'address_id';


    function country()
    {
        return $this->hasOne(Country::class, 'country_id' , 'country_id');
    }
    
    function zone()
    {
        return $this->hasOne(Zone::class, 'zone_id' , 'zone_id');
    }
}
