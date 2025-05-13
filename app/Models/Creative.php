<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Creative extends Model
{
    protected $table = 'creatives';

    protected $fillable = [
        'id',
        'name',
        'path',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrl()
    {
        return Storage::disk('s3')->url($this->path);
    }
}
