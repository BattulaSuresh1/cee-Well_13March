<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartMeasurements extends Model
{
    use HasFactory;

    public function precalvalues()
    {
        return $this->hasMany(PrecalValues::class,'cart_id','cart_id');
    }

    public function thickness()
    {
        return $this->hasMany(Thickness::class,'cart_id','cart_id');
    }
}
