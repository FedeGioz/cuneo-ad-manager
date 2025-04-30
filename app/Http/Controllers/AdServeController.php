<?php

namespace App\Http\Controllers;

use App\Models\GuestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ipinfo\ipinfo\IPinfo;
use Laravel\Jetstream\Agent;

class AdServeController extends Controller
{
    // TODO: api che fetcha tutto dalla richiesta e restituisce l'ad da visualizzare, capire se registrare impression ora o client

    public function return_device_info(Request $request){
        $connection_type = $request->input('connection_type');

        $agent = new Agent();

        $device_type = $agent->isDesktop() ? 'desktop' : ($agent->isMobile() || $agent->isTablet() ? 'mobile' : 'All');

        # Problema con il metodo is per macOS
        if($agent->platform() == 'OS X') $device_os = 'MacOS';
        else{
            if ($agent->is('Windows')) {
                $device_os = 'Windows';
            } elseif ($agent->is('Linux')) {
                $device_os = 'Linux';
            } elseif ($agent->is('Android')) {
                $device_os = 'Android';
            } elseif ($agent->is('iOS')) {
                $device_os = 'iOS';
            } else {
                $device_os = 'All';
            }
        }

        $browser = $agent->browser();

        $client = new IPinfo(env('IPINFO_API_KEY'));
        $details = $client->getDetails($request->ip());

        if(!DB::table('guest_users')->where('ip', $request->ip())->where('user_agent', $request->header('User-Agent'))->exists()){
            GuestUser::create([
                'ip' => $details->ip,
                'user_agent' => $request->header('User-Agent'),
                'country' => $details->country,
                'city' => $details->city,
                'isp' => $details->asn,
                'connection_type' => $connection_type,
                'device_os' => $device_os,
                'device_type' => $device_type,
                'device_browser' => $browser,
                'device_language' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
            ]);
        }

        return response()->json(GuestUser::find());
    }
}
