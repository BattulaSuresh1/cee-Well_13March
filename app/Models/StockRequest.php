<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;
     protected $table = 'request';

    public function inventories(){
        return $this->hasMany(Inventory::class,'request_id','id');
    }

    public function store(){
        return $this->hasOne(Store::class,'id','store_id');
    }
}
