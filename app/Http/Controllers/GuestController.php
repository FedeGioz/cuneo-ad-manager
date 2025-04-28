<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ipinfo\ipinfo\IPinfo;

class GuestController extends Controller
{
    public function index(Request $request){
        return view('guest.index');
    }

    public function data(Request $request){
        $access_token = '9021320ead3454';
        $client = new IPinfo($access_token);
        $details = $client->getDetails($request->ip());

        echo $details->city;
    }
}
