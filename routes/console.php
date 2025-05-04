<?php

use App\Jobs\CreateDailyPerformances;
use App\Models\Campaign;
use App\Models\DailyPerformance;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Schedule::call(function () {
    Campaign::all()->each(function (Campaign $campaign) {
        CreateDailyPerformances::dispatch($campaign);
    });
})->daily();


//Campaign::all()->each(function (Campaign $campaign) {
//    CreateDailyPerformances::dispatch($campaign);
//});
