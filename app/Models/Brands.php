<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;

    protected $table = 'brands';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'category'
    ];

 

       // Assuming you have a 'category_id' column in your 'brands' table
       public function categories()
       {
           return $this->belongsToMany(ItemType::class,  'item_type');
       }
}
