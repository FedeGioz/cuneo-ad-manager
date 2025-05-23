<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'id',
        'name', // /
        'status', // Active, Paused, Deleted
        'device', # All, Desktop, Mobile
        'ad_title',
        'ad_description',
        'ad_category', # Ristoranti, Negozi etc. /
        'geo_targeting', # All, Country, Region, City --> prendere con GPS, se declina usare ip solo max country
        'isp_targeting',
        'os_targeting', # Android, iOS, Windows, Mac, Linux
        'browser_targeting', # Chrome, Firefox, Safari, Opera, Edge
        'browser_language_targeting', # Italiano, Inglese, Francese, Spagnolo
        'keyword_targeting', # Con click passati salvare in sessione le keyword relative ad annuncio, riproporre targeting
        'max_bid',
        'start_date', # Data inizio
        'end_date', # Data fine /
        'daily_budget', // /
        'target_url',
        'user_id',
        'creative_id'
    ];

    protected $casts = [
        'keyword_targeting' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function creative()
    {
        return $this->belongsTo(Creative::class);
    }
}
