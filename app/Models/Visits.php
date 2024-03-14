<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    use HasFactory;

     /**
     * Get the customers record associated with the visit.
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

   
}
