<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rp extends Model
{
    use HasFactory;
    protected $table = 'rp';
    public $timestamps = false;
    protected $fillable = [
        'rp',
    ];

}
