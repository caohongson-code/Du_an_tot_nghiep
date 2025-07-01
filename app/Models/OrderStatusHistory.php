<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    // Các cột có thể gán giá trị
    protected $fillable = ['order_id', 'order_status_id', 'description', 'updated_by'];

    // Quan hệ với bảng orders
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ với bảng order_statuses
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    // Quan hệ với bảng users (người cập nhật)
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
