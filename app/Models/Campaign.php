<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'id',
        'name',
        'device', # All, Desktop, Mobile
        'ad_format', # Display, Video
        'ad_type', # Static Banner, Video Banner (solo x display banner)
        'ad_width', # 950x250, 315x300, 300x250, 468x60, 305x99, 320x480, 300x100, (16 o 9 x video)
        'ad_height', # (dimensioni sopra), (16 o 9 x video)
        'ad_category', # Ristoranti, Negozi etc.
        'geo_targeting', # All, Country, Region, City --> prendere con GPS, se declina usare ip solo max country
        'income_targeting', # Lista di quartieri ricchi e poveri se attivato GPS abbinato a provider (Vodafone etc ricchi. Kena, Lyca poveri)
        'isp_targeting',
        'ip_targeting', # Target su range di ip
        'wifi_cellular_targeting', # Wifi, Cellular
        'os_targeting', # Android, iOS, Windows, Mac, Linux
        'browser_targeting', # Chrome, Firefox, Safari, Opera, Edge
        'browser_language_targeting', # Italiano, Inglese, Francese, Spagnolo
        'keyword_targeting', # Con click passati salvare in sessione le keyword relative ad annuncio, riproporre targeting
        'max_bid',
        'start_date', # Data inizio
        'end_date', # Data fine
        'frequency_capping', # Max n impression al giorno per utente
        'daily_budget',
        'target_url',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function creatives()
    {
        return $this->belongsToMany(Creative::class);
    }
}
