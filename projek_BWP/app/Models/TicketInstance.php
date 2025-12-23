<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketInstance extends Model
{
    protected $primaryKey = 'ticket_instance_id';
    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'user_id',
        'qr_code',
        'status',
        'valid_until',
        'used_at'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
