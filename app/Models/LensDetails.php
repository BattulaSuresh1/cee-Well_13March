<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LensDetails extends Model
{
    use HasFactory;

    
    protected $table = 'lens_details';
    public $timestamps = false;
    protected $fillable = [
        'eye_selection',
        'brand',
        'type',
        'index',
        'dia',
        'from',
        'to',
        'rp',
        'max_cyl',
        'code',
        'name',
        'mrp',
        'cost_price',

    ];
}
