<?php

namespace App\Models;

use Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public function measurements()
    {
        return $this->belongsTo(ItemType::class,'id','item_type');
    }

    // public function brand()
    // {
    //     return $this->belongsTo(Brands::class, 'brand'); // Assuming 'brand' is the foreign key in Products table
    // }
   
}
