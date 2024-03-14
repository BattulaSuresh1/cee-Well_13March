<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;
use App\Models\Permission;

class RoleHasPermission extends Model
{
    use HasFactory; 

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public $timestamps = false;

    public function role()
    {
        return $this->hasOne(role::class,'id','role_id');
    }

    public function permission()
    {
        return $this->hasOne(permission::class,'id','permission_id');
    }
}
