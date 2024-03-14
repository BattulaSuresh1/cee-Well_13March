<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartLens extends Model
{
    use HasFactory;

    protected $table = 'cart_lenses';
    // public $timestamps = false;
    protected $fillable = [
        'eye_selection',
        'cart_id',
        'name',
        'code',
        'brand',
        'type',
        'index',
        'dia',
        'from',
        'to',
        'rp',
        'max_cyl',
        'mrp',
        'cost_price',
    ];
}
