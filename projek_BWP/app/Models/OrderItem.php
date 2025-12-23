<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'schedule_id',
        'seat_id',
        'price'
    ];

    // 🔗 Belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // 🔗 Belongs to Ticket Product
    public function product()
    {
        return $this->belongsTo(TicketProduct::class, 'product_id', 'product_id');
    }

    // 🔗 Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    // 🔗 Seat
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    // 🔗 Ticket Instance
    public function ticketInstance()
    {
        return $this->hasOne(TicketInstance::class, 'order_item_id');
    }
}
