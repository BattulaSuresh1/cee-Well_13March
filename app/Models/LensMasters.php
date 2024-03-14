<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LensMasters extends Model
{
    use HasFactory;
    protected $table = 'lens_masters';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'code',
        'brand',
        'type',
        'index',
        'dia',
        'from',
        'to',
        'rp',
        'max_cyl'
    ];
}
