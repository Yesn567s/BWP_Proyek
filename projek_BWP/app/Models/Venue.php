<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $primaryKey = 'venue_id';
    public $timestamps = false;

    public function studios()
    {
        return $this->hasMany(Studio::class, 'venue_id', 'venue_id');
    }
}
