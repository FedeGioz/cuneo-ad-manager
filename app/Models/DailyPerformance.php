<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPerformance extends Model
{
    protected $table = 'daily_performance';
    protected $fillable = [
        'id',
        'date',
        'impressions',
        'clicks',
        'cost',
        'campaign_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
