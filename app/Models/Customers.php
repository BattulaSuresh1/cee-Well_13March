<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customers extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'status', 'password' , 'code',
    ];

    public function customerBranch()
    {
        return $this->hasOne("App\Models\CustomerBranch", 'customer_id');
    }

    public function visits()
    {
        return  $this->hasMany(Visits::class, 'customer_id', 'id');
    }

}
