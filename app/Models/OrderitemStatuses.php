<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemStatuses extends Model
{
    use HasFactory;
    public $timestamps = false;

    function status() {
        return $this->belongsTo(StatusList::class, 'status_id', 'id');
    }

    function user() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    function orderItem() {
        return $this->belongsTo(OrderItems::class, 'order_item_id', 'id');
    }

}
