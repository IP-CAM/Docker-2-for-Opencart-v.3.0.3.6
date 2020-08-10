<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponHistory extends Model
{
    protected $table = 'coupon_history';
    public $timestamps = false;
    protected $primaryKey = 'coupon_history_id';

}