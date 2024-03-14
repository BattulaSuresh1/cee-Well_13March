<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Spatie\Permission\Models\Role as Model;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;   

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

}