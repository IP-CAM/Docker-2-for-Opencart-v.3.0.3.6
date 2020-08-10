<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Country;
use App\Zone;
use App\Order_history;
use App\Order_product;
use App\Order_total;
use App\Order_status;
use App\Currency;

class Order extends Model
{
    protected $table = 'order';
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';
    protected $primaryKey = 'order_id';

    protected $hidden = [
        'forwarded_ip' , 'user_agent' , 'accept_language' , 'ip' , 'currency_value'
    ];


    function country()
    {
        return $this->hasOne(Country::class, 'country_id');
    }
    
    function zone()
    {
        return $this->hasOne(Zone::class, 'zone_id');
    }

    function status() {
        return $this->hasMany(Order_status::class, 'order_status_id' , 'order_status_id');   
    }
    
    function products() {
       return $this->hasMany(Order_product::class, 'order_id' , 'order_id');   
    }
    
    function currency() {
       return $this->hasOne(Currency::class, 'currency_id');   
    }

    
}
