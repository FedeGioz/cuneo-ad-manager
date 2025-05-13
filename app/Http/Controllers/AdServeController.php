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

class AdServeController extends Controller
{
    public function device_info(Request $request){
        if($request->session()->exists('guestUser')){
            return response()->json([],204);
        }
        $guestUser = DB::table('guest_users')->where('ip', $request->ip())->where('user_agent', $request->header('User-Agent'))->first();
        if(!$guestUser){
            $agent = new Agent();

            $device_type = $agent->isDesktop() ? 'desktop' : ($agent->isMobile() || $agent->isTablet() ? 'mobile' : 'All');

            if (str_contains($agent->platform(), 'Windows')) {
                $device_os = 'Windows';
            } elseif (str_contains($agent->platform(), 'Linux')) {
                $device_os = 'Linux';
            } elseif (str_contains($agent->platform(), 'Android')) {
                $device_os = 'Android';
            } elseif (str_contains($agent->platform(), 'iOS')) {
                $device_os = 'iOS';
            } elseif (str_contains($agent->platform(), 'OS X')) {
                $device_os = 'MacOS';
            } else {
                $device_os = 'All';
            }

            $browser = $agent->browser();

            $client = new IPinfo(env('IPINFO_API_KEY'));
            $details = $client->getDetails($request->ip());

            $guestUser = GuestUser::create([
                'ip' => $details->ip,
                'user_agent' => $request->header('User-Agent'),
                'country' => $details->country,
                'city' => $details->city,
                'isp' => $details->org,
                'device_os' => $device_os,
                'device_type' => $device_type,
                'device_browser' => $browser,
                'device_language' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
            ]);
        }

        $request->session()->put('guestUser', $guestUser);

        return response()->json(["status" => "created"], 201);
    }

    public function match(Request $request){
        $guestUser = $request->session()->get('guestUser') ??
            GuestUser::where('ip', $request->ip())->where('user_agent', $request->header('User-Agent'))->first();

        if(!$guestUser){
            error_log("ENTRATO NEL DEVICE INFO NO GUEST");
            $this->device_info($request);
        }

        $campaigns = Campaign::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            # Device matching
            ->where(function($query) use ($guestUser) {
                $query->where('device', $guestUser->device_type)
                    ->orWhere('device', 'all');
            })
            # Geo Targeting
            ->where(function($query) use ($guestUser) {
                $query->where('geo_targeting', $guestUser->country)
                    ->orWhere('geo_targeting', $guestUser->city)
                    ->orWhere('geo_targeting', 'all');
            })
            ->where(function($query) use ($guestUser) {
                $query->whereRaw("? LIKE CONCAT('%', isp_targeting, '%')", [$guestUser->isp])
                    ->orWhere('isp_targeting', 'all');
            })
            ->where(function($query) use ($guestUser) {
                $query->whereRaw("? LIKE CONCAT('%', os_targeting, '%')", [$guestUser->device_os])
                    ->orWhere('os_targeting', 'all');
            })
            ->where(function($query) use ($guestUser) {
                $query->whereRaw("? LIKE CONCAT('%', browser_targeting, '%')", [$guestUser->device_browser])
                    ->orWhere('browser_targeting', 'all');
            })
            ->where(function($query) use ($guestUser) {
                $query->whereRaw("? LIKE CONCAT('%', browser_language_targeting, '%')", [$guestUser->device_language])
                    ->orWhere('browser_language_targeting', 'all');
            })
            ->where(function($query) use ($guestUser) {
                $query->whereRaw("? LIKE CONCAT('%', keyword_targeting, '%')", [$guestUser->keywords])
                    ->orWhere('keyword_targeting', 'all');
            })
            ->where(function($query) use ($request) {
                if($request->input('category')){
                    $query->where('ad_category', $request->input('category'));
                }
            })
            ->orderByDesc('max_bid')
            ->get();

        $availableCampaigns = [];

        foreach ($campaigns as $campaign) {
            $campaignOwner = $campaign->user;

            if ($campaignOwner && $campaignOwner->balance >= $campaign->max_bid) {
                DailyPerformance::where('campaign_id', $campaign->id)
                    ->where('date', now()->format('Y-m-d'))
                    ->increment('impressions');

                $availableCampaigns[] = [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'ad_title' => $campaign->ad_title,
                    'ad_description' => $campaign->ad_description,
                    'ad_category' => $campaign->ad_category,
                    'ad_format' => $campaign->ad_format,
                    'ad_type' => $campaign->ad_type,
                    'target_url' => $campaign->target_url,
                    'creative_path' => $campaign->creative->getUrl()
                ];
            }
        }

        if (empty($availableCampaigns)) {
            return response()->json(['message' => 'No matching campaign found'], 404);
        }

        return response()->json($availableCampaigns);
    }

    public function redirect(Request $request){
        $campaignId = $request->get('campaignId');
        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        $guestUser = $request->session()->get('guestUser') ??
            GuestUser::where('ip', $request->ip())->where('user_agent', $request->header('User-Agent'))->first();

        if ($guestUser && $campaign->keyword_targeting && $campaign->keyword_targeting !== 'all') {
            // Extract the first keyword from campaign keywords
            $campaignKeywords = $campaign->keyword_targeting;
            if (!is_array($campaignKeywords)) {
                $campaignKeywords = explode(',', str_replace(['[', ']', '"', "'"], '', $campaignKeywords));
            }

            if (!empty($campaignKeywords)) {
                $keywordToAdd = trim($campaignKeywords[0]);

                // Check if the keyword already exists using LIKE approach
                $keywordExists = false;

                if (!empty($guestUser->keywords)) {
                    $keywordExists = DB::select(
                        "SELECT 1 WHERE ? LIKE CONCAT('%', ?, '%')",
                        [$guestUser->keywords, $keywordToAdd]
                    );
                }

                // Add keyword if not found
                if (!$keywordExists) {
                    $userKeywords = !empty($guestUser->keywords) ?
                        json_decode($guestUser->keywords, true) : [];

                    if (!is_array($userKeywords)) {
                        $userKeywords = [];
                    }

                    $userKeywords[] = $keywordToAdd;

                    $guestUser->keywords = json_encode($userKeywords);
                    $guestUser->save();

                    $request->session()->put('guestUser', $guestUser);
                }
            }
        }

        DailyPerformance::where('campaign_id', $campaignId)
            ->where('date', now()->format('Y-m-d'))
            ->increment('clicks');

        User::where('id', $campaign->user_id)
            ->decrement('balance', $campaign->max_bid);

        return redirect()->away($campaign->target_url);
    }
}
