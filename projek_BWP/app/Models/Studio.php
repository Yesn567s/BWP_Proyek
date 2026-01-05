<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $primaryKey = 'studio_id';
    public $timestamps = false;

    protected $fillable = [
        'venue_id',
        'studio_name',
        'studio_type',
    ];

    protected static function booted()
{
    static::created(function ($studio) {

        $rows = ['A', 'B', 'C', 'D'];
        $seatsPerRow = 8;

        foreach ($rows as $row) {
            for ($i = 1; $i <= $seatsPerRow; $i++) {
                Seat::create([
                    'studio_id'  => $studio->studio_id,
                    'row_letter' => $row,        // ✅ EXACT column name
                    'seat_number'=> $i,          // ✅ EXACT column name
                    'status'     => 'available', // optional
                ]);
            }
        }
    });
}

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'studio_id', 'studio_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
