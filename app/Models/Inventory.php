<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public function product() {
        return $this->hasOne(Products::class,'id','product_id');
    }

    public function stockrequest(){
        return $this->belongsTo(StockRequest::class);
    }
    
}
