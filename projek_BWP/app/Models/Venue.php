<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $primaryKey = 'venue_id';

    // public function foods()
    // {
    //     return $this->hasMany(Food::class, 'venue_id');
    // }
}
