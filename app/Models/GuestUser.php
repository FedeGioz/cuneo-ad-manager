<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    protected $table = 'guest_users';

    protected $fillable = [
        'ip',
        'user_agent',
        'country',
        'city',
        'isp',
        'device_os',
        'device_type',
        'device_browser',
        'device_language',
        'keywords'
    ];
}
