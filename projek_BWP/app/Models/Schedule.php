<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'venue_id',
        'start_datetime',
        'end_datetime',
    ];

    public function product()
    {
        return $this->belongsTo(TicketProduct::class, 'product_id', 'product_id');
    }
}
