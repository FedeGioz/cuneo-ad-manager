<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\DailyPerformance;
use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ipinfo\ipinfo\IPinfo;
use Laravel\Jetstream\Agent;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $categories = Campaign::where('status', 'active')
            ->distinct()
            ->pluck('ad_category')
            ->filter();

        $adServeController = new AdServeController();

        $matchResponse = $adServeController->match($request);

        $featuredAds = [];
        if ($matchResponse->status() == 200) {
            $responseData = $matchResponse->getData(true);

            $featuredAds = [];
            foreach ($responseData as $ad) {
                $featuredAds[] = [
                    'id' => $ad['id'],
                    'name' => $ad['name'],
                    'ad_title' => $ad['ad_title'],
                    'ad_description' => $ad['ad_description'],
                    'creative_path' => $ad['creative_path'],
                    'ad_category' => $ad['ad_category']
                ];
            }
        }

        error_log("MATCHING: ".print_r($featuredAds, true));

        return view('guest.index', [
            'featuredAds' => $featuredAds,
            'categories' => $categories
        ]);
    }
}
