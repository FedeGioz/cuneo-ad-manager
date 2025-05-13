<?php

namespace App\Http\Controllers;

use App\Jobs\CreateDailyPerformances;
use App\Models\Campaign;
use App\Models\Creative;
use App\Models\DailyPerformance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdvertiserController extends Controller
{
    public function index(Request $request){
        $userId = $request->user()->id;
        # Metodo pluck = select che prende i valori della chiave selezionata
        $userCampaigns = Campaign::where('user_id', $userId)->pluck('id');

        $campaignStats = DailyPerformance::whereIn('campaign_id', $userCampaigns)
            ->selectRaw('campaign_id, SUM(impressions) as total_impressions, SUM(clicks) as total_clicks')
            ->groupBy('campaign_id')
            ->get()
            ->keyBy('campaign_id')
            ->map(function ($item) {
                return [$item->total_impressions, $item->total_clicks];
            })
            ->toArray();

        return view('advertisers.index')->with([
            'campaigns' => Campaign::where('user_id', $userId)->get(),
            'totalImpressions' => DailyPerformance::whereIn('campaign_id', $userCampaigns)->sum('impressions'),
            'totalClicks' => DailyPerformance::whereIn('campaign_id', $userCampaigns)->sum('clicks'),
            'campaignStats' => $campaignStats,
        ]);
    }

    public function showCreateCampaign(Request $request){
        return view('advertisers.campaigns.create');
    }

    public function createCampaign(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'device' => 'required|string',
                'ad_title' => 'required|string|max:255',
                'ad_description' => 'required|string|max:255',
                'target_url' => 'required|url|max:255',
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
                'image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->filled('keyword_targeting')) {
                $keywordArray = array_map('trim', explode(',', $request->keyword_targeting));
            }

            if ($request->filled('isp_targeting')) {
                $ispArray = array_map('trim', explode(',', $request->isp_targeting));
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();

                try {
                    $path = Storage::disk('s3')->putFileAs('creatives', $file, $fileName);

                    $creative = Creative::create([
                        'name' => $fileName,
                        'path' => $path,
                        'user_id' => auth()->id()
                    ]);

                    $creativeId = $creative->id;
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            $campaign = Campaign::create([
                'name' => $validated['name'],
                'status' => 'paused',
                'device' => strtolower($validated['device']),
                'ad_title' => $validated['ad_title'],
                'ad_description' => $validated['ad_description'],
                'ad_category' => strtolower($validated['ad_category']),
                'geo_targeting' => $validated['geo_targeting'] ?? 'all',
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
                'creative_id' => $creativeId ?? null
            ]);

            CreateDailyPerformances::dispatch($campaign);

            return redirect()->route('advertisers.index')->with('success', 'Campaign created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create campaign: ' . $e->getMessage()])->withInput();
        }
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

        $campaign = Campaign::where('id', $campaignId)->where('user_id', $request->user()->id);
        Creative::where('id', $campaign->first()->creative_id)->where('user_id', $request->user()->id)->delete();
        DailyPerformance::where('campaign_id', $campaignId)->delete();
        $campaign->delete();

        return redirect($request->url());
    }

    public function settings(Request $request){
        return view('advertisers.settings');
    }

    public function updateSettings(Request $request){
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|numeric|max:20',
            'country' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->company_name = $validated['company_name'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->zip_code = $validated['zip_code'];
        $user->country = $validated['country'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('advertisers.settings')->with('success', 'Le impostazioni sono state aggiornate con successo.');
    }

    public function showStatistics(Request $request)
    {
        $userId = $request->user()->id;
        $allCampaigns = Campaign::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $campaignId = $request->get('campaignId');
        $campaignStats = collect();

        $filteredCampaigns = $campaignId ? $allCampaigns->where('id', $campaignId) : $allCampaigns;
        $campaignIds = $filteredCampaigns->pluck('id')->toArray();

        foreach ($filteredCampaigns as $campaign) {
            $performance = DailyPerformance::where('campaign_id', $campaign->id)->get();

            $impressions = $performance->sum('impressions');
            $clicks = $performance->sum('clicks');
            $cost = $clicks * $campaign->max_bid;

            $campaignStats->push((object)[
                'id' => $campaign->id,
                'name' => $campaign->name,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'ctr' => $impressions > 0 ? ($clicks / $impressions * 100) : 0,
                'cost' => $cost,
                'ecpm' => $impressions > 0 ? ($cost / $impressions * 1000) : 0,
                'ecpc' => $clicks > 0 ? ($cost / $clicks) : 0
            ]);
        }

        $chartData = DailyPerformance::whereIn('campaign_id', $campaignIds)
            ->selectRaw('date, SUM(impressions) as total_impressions, SUM(clicks) as total_clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'date' => $item->date,
                    'aggregate' => $item->total_impressions > 0
                        ? round(($item->total_clicks / $item->total_impressions * 100), 2)
                        : 0
                ];
            });

        return view('advertisers.statistics', [
            'campaigns' => $allCampaigns,
            'data' => $chartData,
            'selectedCampaignId' => $campaignId ?? null,
            'campaignStats' => $campaignStats
        ]);
    }

    public function edit(Request $request)
    {
        $campaignId = $request->get('id');
        $campaign = Campaign::where('id', $campaignId)->where('user_id', $request->user()->id)->first();
        if ($campaign->user_id !== $request->user()->id) {
            return redirect()->route('advertisers.index')
                ->with('error', 'Non sei autorizzato a modificare questa campagna.');
        }

        return view('advertisers.campaigns.create', compact('campaign'));
    }

    public function updateCampaign(Request $request, Campaign $campaign)
    {
        if ($campaign->user_id !== $request->user()->id) {
            return redirect()->route('advertisers.index')
                ->with('error', 'Non sei autorizzato a modificare questa campagna.');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'device' => 'required|string',
                'ad_title' => 'required|string|max:255',
                'ad_description' => 'required|string|max:255',
                'target_url' => 'required|url|max:255',
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
                'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048', // Made optional for updates
                'status' => 'required|string|in:active,paused,completed',
            ]);

            if ($request->filled('keyword_targeting') && $request->keyword_targeting !== 'all') {
                $campaign->keyword_targeting = array_map('trim', explode(',', $request->keyword_targeting));
            } else {
                $campaign->keyword_targeting = 'all';
            }

            if ($request->filled('isp_targeting') && $request->isp_targeting !== 'all') {
                $campaign->isp_targeting = array_map('trim', explode(',', $request->isp_targeting));
            } else {
                $campaign->isp_targeting = 'all';
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();

                try {
                    $path = Storage::disk('s3')->putFileAs('creatives', $file, $fileName);

                    $creative = Creative::create([
                        'name' => $fileName,
                        'path' => $path,
                        'user_id' => auth()->id()
                    ]);

                    $campaign->creative_id = $creative->id;
                } catch (Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            $campaign->name = $validated['name'];
            $campaign->device = strtolower($validated['device']);
            $campaign->ad_title = $validated['ad_title'];
            $campaign->ad_description = $validated['ad_description'];
            $campaign->ad_category = strtolower($validated['ad_category']);
            $campaign->geo_targeting = $validated['geo_targeting'] ?? 'all';
            $campaign->os_targeting = $validated['os_targeting'] ?? 'all';
            $campaign->browser_targeting = $validated['browser_targeting'] ?? 'all';
            $campaign->browser_language_targeting = $validated['browser_language_targeting'] ?? 'all';
            $campaign->max_bid = $validated['max_bid'];
            $campaign->start_date = $validated['start_date'];
            $campaign->end_date = $validated['end_date'];
            $campaign->daily_budget = $validated['daily_budget'];
            $campaign->target_url = $validated['target_url'];
            $campaign->status = $validated['status'];

            $campaign->save();

            return redirect()->route('advertisers.index')->with('success', 'Campagna aggiornata con successo!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update campaign: ' . $e->getMessage()])->withInput();
        }
    }
}
