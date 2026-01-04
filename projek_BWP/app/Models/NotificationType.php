<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationType extends Model
{
    protected $table = 'notification_type';
    protected $fillable = ['code', 'title', 'description'];
    public $timestamps = false;

    public function userSettings(): HasMany
    {
        return $this->hasMany(UserNotificationSetting::class, 'notification_type_id', 'id');
    }
}
