<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $table = 'ticket_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(
            TicketProduct::class,
            'category_id',
            'category_id'
        );
    }
}
