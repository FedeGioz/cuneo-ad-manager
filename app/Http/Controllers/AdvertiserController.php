<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(){
        return view('advertisers.index')->with(['campaigns'=> Campaign::all()]);
    }

    public function showCreateCampaign(Request $request){
        return view('advertisers.campaigns.create');
    }

    public function createCampaign(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'device' => 'required|string|max:255',
            'ad_title' => 'required|string|max:255',
            'ad_description' => 'required|string|max:255',
            'ad_format' => 'required|string|max:50',
            'ad_type' => 'required|string|max:50',
            'target_url' => 'required|string|max:255',
            'ad_width' => 'required|int|max:50',
            'ad_height' => 'required|int|max:50',
            'ad_category' => 'required|string|max:50',
            'geo_targeting' => 'required|string|max:50',
            'income_targeting' => 'required|string|max:50',
            'isp_targeting' => 'required|string|max:50',
            'ip_targeting' => 'required|string|max:50',
            'wifi_cellular_targeting' => 'required|string|max:50',
            'os_targeting' => 'required|string|max:50',
            'browser_targeting' => 'required|string|max:50',
            'browser_language_targeting' => 'required|string|max:50',
            'keyword_targeting' => 'required|string|max:50',
            'max_bid' => 'required|int|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'frequency_capping' => 'required|int|max:100',
            'daily_budget' => 'required|int|max:100000'
        ]);

        Campaign::create([
            'name' => $request->get('name'),
            'device' => $request->get('device'),
            'ad_title' => $request->get('ad_title'),
            'ad_description' => $request->get('ad_description'),
            'ad_format' => $request->get('ad_format'),
            'ad_type' => $request->get('ad_type'),
            'target_url' => $request->get('target_url'),
            'ad_width' => $request->get('ad_width'),
            'ad_height' => $request->get('ad_height'),
            'ad_category' => $request->get('ad_category'),
            'geo_targeting' => $request->get('geo_targeting'),
            'income_targeting' => $request->get('income_targeting'),
            'isp_targeting' => $request->get('isp_targeting'),
            'ip_targeting' => $request->get('ip_targeting'),
            'wifi_cellular_targeting' => $request->get('wifi_cellular_targeting'),
            'os_targeting' => $request->get('os_targeting'),
            'browser_targeting' => $request->get('browser_targeting'),
            'browser_language_targeting' => $request->get('browser_language_targeting'),
            'keyword_targeting' => $request->get('keyword_targeting'),
            'max_bid' => $request->get('max_bid'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'frequency_capping' => $request->get('frequency_capping'),
            'daily_budget' => $request->get('daily_budget'),
            'user_'
        ]);
    }
}
