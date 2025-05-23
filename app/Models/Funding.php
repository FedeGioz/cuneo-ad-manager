<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{
    protected $table = 'fundings';

    protected $fillable = [
        'id',
        'amount',
        'user_id',
        'status',
        'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
