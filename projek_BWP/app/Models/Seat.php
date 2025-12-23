<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';
    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'venue_id',
        'seat_code',
    ];

    /**
     * The venue this seat belongs to
     */
    // public function venue()
    // {
    //     return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    // }

    /**
     * Order items associated with this seat
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'seat_id', 'seat_id');
    }
}
