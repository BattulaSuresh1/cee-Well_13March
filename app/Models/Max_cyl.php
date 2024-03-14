<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Max_cyl extends Model
{
    use HasFactory;
    protected $table = 'max_cyl';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

}
