<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    public function lenspower()
    {
        return $this->hasMany(LensPower::class,'prescription_id','id' ,'customer_id');
    }

    public function precalvalues()
    {
        return $this->hasMany(PrecalValues::class,'prescription_id','id');
    }

    public function thickness()
    {
        return $this->hasMany(Thickness::class,'prescription_id','id');
    }
}
