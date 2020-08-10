<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTotal extends Model
{
    protected $table = 'order_total';
    public $timestamps = false;
    protected $primaryKey = 'order_total_id';

}