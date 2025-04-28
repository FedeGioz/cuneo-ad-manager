<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class AdServeController extends Model
{
    // TODO: api che fetcha tutto dalla richiesta e restituisce l'ad da visualizzare, capire se registrare impression ora o client side
    public function serve(Request $request){
        $userAgent = $request->userAgent();
        if(str_contains($userAgent, 'Android' | str_contains($userAgent, 'iPhone') | str_contains($userAgent, 'iPad'))){
            $device_type = 'mobile';
        }
        else $device_type = 'desktop';
    }
}
