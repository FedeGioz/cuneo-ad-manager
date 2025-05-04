<?php

namespace App\Http\Controllers;

use App\Jobs\CreateDailyPerformances;
use App\Models\Campaign;
use App\Models\Creative;
use App\Models\DailyPerformance;
use App\Models\Funding;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(Request $request){
        $userId = $request->user()->id;
        # Metodo pluck = select che prende i valori della chiave selezionata
        $userCampaigns = Campaign::where('user_id', $userId)->pluck('id');

        return view('advertisers.index')->with([
            'campaigns' => Campaign::where('user_id', $userId)->get(),
            'totalImpressions' => DailyPerformance::whereIn('campaign_id', $userCampaigns)->sum('impressions'),
            'totalClicks' => DailyPerformance::whereIn('campaign_id', $userCampaigns)->sum('clicks')
        ]);
    }

    public function showCreateCampaign(Request $request){
        return view('advertisers.campaigns.create', ['countries' => ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait","Kyrgyz Republic","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania","Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Satellite","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","St. Lucia","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia","Turkey","Turkmenistan","Turks &amp; Caicos","Uganda","Ukraine","United Arab Emirates","United Kingdom","Uruguay","Uzbekistan","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"]]);
    }

    public function createCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device' => 'required|string',
            'ad_title' => 'required|string|max:255',
            'ad_description' => 'required|string|max:255',
            'ad_format' => 'required|string|max:50',
            'ad_type' => 'required|string|max:50',
            'target_url' => 'required|url|max:255',
            'ad_width' => 'required|integer',
            'ad_height' => 'required|integer',
            'ad_category' => 'required|string|max:50',
            'geo_targeting' => 'required|string|max:50',
            'os_targeting' => 'required|string',
            'browser_targeting' => 'required|string',
            'browser_language_targeting' => 'required|string',
            'keyword_targeting' => 'nullable|string',
            'isp_targeting' => 'nullable|string',
            'max_bid' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'daily_budget' => 'required|numeric',
            'image' => 'required|file|max:2048|mimes:jpeg,png,jpg,gif,mp4',
        ]);

        $keywordArray = null;
        if ($request->filled('keyword_targeting')) {
            $keywordArray = array_map('trim', explode(',', $request->keyword_targeting));
        }

        $ispArray = null;
        if ($request->filled('isp_targeting')) {
            $ispArray = array_map('trim', explode(',', $request->isp_targeting));
        }

        $creative = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('creatives', $fileName, 'public');

            $creative = Creative::create([
                'name' => $fileName,
                'width' => $request->ad_width,
                'height' => $request->ad_height,
                'path' => $path,
                'type' => $request->ad_format === 'video' ? 'video' : 'image',
                'user_id' => auth()->id()
            ]);
        }

        // TODO: mettere ad_category come enum nella migration

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'status' => 'paused',
            'device' => strtolower($validated['device']),
            'ad_title' => $validated['ad_title'],
            'ad_description' => $validated['ad_description'],
            'ad_format' => strtolower($validated['ad_format']),
            'ad_type' => strtolower($validated['ad_type']),
            'ad_width' => $validated['ad_width'],
            'ad_height' => $validated['ad_height'],
            'ad_category' => strtolower($validated['ad_category']),
            'geo_targeting' => strtoupper($validated['geo_targeting']) ?? 'all',
            'isp_targeting' => $ispArray ?? 'all',
            'os_targeting' => $validated['os_targeting'] ?? 'all',
            'browser_targeting' => $validated['browser_targeting'] ?? 'all',
            'browser_language_targeting' => $validated['browser_language_targeting'] ?? 'all',
            'keyword_targeting' => $keywordArray ?? 'all',
            'max_bid' => $validated['max_bid'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'daily_budget' => $validated['daily_budget'],
            'target_url' => $validated['target_url'],
            'user_id' => auth()->id(),
            'creative_id' => $creative ? $creative->id : null
        ]);

        CreateDailyPerformances::dispatch($campaign);

        return redirect()->route('advertisers.index')->with('success', 'Campaign created successfully');
    }

    public function startCampaign(Request $request){
        $campaignId = $request->get('id');
        if(!$campaignId){
            return redirect('/dashboard');
        }

        Campaign::where('id', $campaignId)->where('user_id', $request->user()->id)->update(['status' => 'active']);

        return redirect($request->url());
    }

    public function pauseCampaign(Request $request){
        $campaignId = $request->get('id');
        if(!$campaignId){
            return redirect('/dashboard');
        }

        Campaign::where('id', $campaignId)->where('user_id', $request->user()->id)->update(['status' => 'paused']);

        return redirect($request->url());
    }

    public function deleteCampaign(Request $request){
        $campaignId = $request->get('id');
        if(!$campaignId){
            return redirect('/dashboard');
        }

        Campaign::where('id', $campaignId)->where('user_id', $request->user()->id)->delete();

        return redirect($request->url());
    }

}
