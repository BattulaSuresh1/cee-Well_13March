<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $table = 'item_types';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function brands()
    {
        return $this->belongsToMany(Brands::class, 'category', );
    }
}
