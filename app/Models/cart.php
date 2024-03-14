<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasOne(Products::class,'id','product_id');
    }

    public function customer()
    {
        return $this->hasOne(Customers::class,'id','customer_id');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class,'id','prescription_id');
    }

    public function lens()
    {
        return $this->hasOne(cartLens::class,'id','cart_lenses_id');
    }

    public function measurements()
    {
        return $this->hasOne(cartMeasurements::class,'id','cart_measurements_id');
    }

    public function lensMaster()
    {
        return $this->belongsTo(LensMasters::class, 'lens_master_id' , 'id');
    }
}
