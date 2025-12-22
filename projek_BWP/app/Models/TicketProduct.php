<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketProduct extends Model
{
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(
            TicketCategory::class,
            'category_id',
            'category_id'
        );
    }

    // public function poster()
    // {
    //     return $this->hasOne(
    //         ProductMedia::class,
    //         'product_id',
    //         'product_id'
    //     )->where('media_type', 'poster');
    // }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'product_id', 'product_id');
    }

    public function poster()
    {
        return $this->hasOne(ProductMedia::class, 'product_id', 'product_id');
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class, 'product_id');
    }

}
