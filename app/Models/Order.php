<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number'
    ];

    public function orderitems()
    {
        return $this->hasMany(OrderItems::class,'order_id','id');
    }

    public function customer()
    {
        return $this->hasOne(Customers::class,'id','customer_id');
    }
    
    public function orderpayments()
    {
        return $this->hasMany(OrderPayments::class,'order_id','id');
    }

    public function calculateTotalPaidAmount()
    {
        return $this->orderPayments()->sum('paid_amount');
    }

}
