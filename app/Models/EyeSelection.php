<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeSelection extends Model
{
    use HasFactory;
    protected $table = 'eye_selections';
    public $timestamps = false;
    protected $fillable = [
        'eye_selection	',
    ];

}
