<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $creatives = DB::table('creatives')->pluck('id')->toArray();

        $statuses = ['active', 'paused', 'deleted'];
        $devices = ['all', 'desktop', 'mobile'];
        $formats = ['display', 'video'];
        $types = ['static_banner', 'video_banner'];
        $categories = ['ristoranti', 'tecnologia', 'immobiliare', 'bar', 'aziende', 'supermercati', 'scuole', 'negozi', 'intrattenimento', 'altro'];
        $oses = ['android', 'ios', 'windows', 'mac', 'linux', 'all'];
        $browsers = ['chrome', 'firefox', 'safari', 'opera', 'edge', 'all'];

        $cities = ['Roma', 'Milano', 'Napoli', 'Torino', 'Firenze', 'Bologna', 'Venezia'];
        $keywords = ['offerte', 'sconti', 'promozioni', 'shopping', 'online', 'risparmio'];

        $campaigns = [];

        for ($i = 0; $i < 100; $i++) {
            $width = rand(200, 800);
            $height = rand(100, 600);
            $startDate = Carbon::now()->subDays(rand(0, 30));
            $endDate = (clone $startDate)->addDays(rand(30, 90));

            $campaigns[] = [
                'name' => 'Campagna #' . ($i + 1),
                'status' => $statuses[array_rand($statuses)],
                'ad_title' => 'Titolo Pubblicità #' . ($i + 1),
                'ad_description' => 'Descrizione dell\'annuncio per la campagna ' . ($i + 1),
                'device' => $devices[array_rand($devices)],
                'ad_format' => $formats[array_rand($formats)],
                'ad_type' => $types[array_rand($types)],
                'ad_width' => $width,
                'ad_height' => $height,
                'ad_category' => $categories[array_rand($categories)],
                'geo_targeting' => $cities[array_rand($cities)],
                'isp_targeting' => 'All',
                'os_targeting' => $oses[array_rand($oses)],
                'browser_targeting' => $browsers[array_rand($browsers)],
                'browser_language_targeting' => 'it',
                'keyword_targeting' => json_encode(array_rand(array_flip($keywords), rand(3, 5))),
                'max_bid' => round(mt_rand(100, 300) / 100, 2),
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'daily_budget' => round(mt_rand(1000, 10000) / 100, 2),
                'target_url' => 'https://www.example.com/campaign-' . ($i + 1),
                'user_id' => $users[array_rand($users)],
                'creative_id' => $creatives[array_rand($creatives)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('campaigns')->insert($campaigns);
    }
}
