<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $primaryKey = 'studio_id';
    public $timestamps = false;

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'studio_id', 'studio_id');
    }
}
