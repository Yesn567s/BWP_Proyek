<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'voucher_id';
    // public $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'max_usage',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // public function categories()
    // {
    //     return $this->belongsToMany(
    //         TicketCategory::class,
    //         'voucher_categories',
    //         'voucher_id',
    //         'category_id'
    //     );
    // }
}
