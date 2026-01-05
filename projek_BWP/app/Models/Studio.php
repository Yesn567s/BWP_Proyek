<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seat;

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
        static::created(function (Studio $studio) {
            $rows = ['A', 'B', 'C', 'D'];
            $seatsPerRow = 8;

            $seatsToInsert = [];
            foreach ($rows as $row) {
                for ($i = 1; $i <= $seatsPerRow; $i++) {
                    $seatsToInsert[] = [
                        'studio_id'   => $studio->studio_id,
                        'row_letter'  => $row,
                        'seat_number' => $i,
                        'status'      => 'available',
                    ];
                }
            }

            // Bulk insert so all seats are created together after the studio is saved.
            Seat::insert($seatsToInsert);
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
