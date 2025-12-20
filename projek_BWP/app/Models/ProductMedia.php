<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    protected $primaryKey = 'media_id';

    protected $fillable = [
        'product_id',
        'media_type',
        'media_url',
        'is_primary'
    ];

    public function product()
    {
        return $this->belongsTo(TicketProduct::class, 'product_id');
    }
}
