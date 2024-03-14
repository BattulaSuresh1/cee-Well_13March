<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LensPower extends Model
{
    use HasFactory;
    
    public function prescription()
    {
        return $this->belongsTo("App\Models\Prescription", 'prescription_id');
    }

    // public function prescription()
    // {
    //     return $this->belongsTo(Prescription::class,'prescription_id','id');
    // }
}
