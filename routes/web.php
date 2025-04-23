<?php

use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestController::class, 'index'])->name('guest.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [AdvertiserController::class, 'index'])->name('advertisers.index');
    Route::get('/advertisers/create', [AdvertiserController::class, 'showCreateCampaign'])->name('advertisers.campaigns.showCreate');
    Route::post('/advertisers/create', [AdvertiserController::class, 'createCampaign'])->name('advertisers.campaigns.create');
    Route::get('/advertisers/statistics', [AdvertiserController::class, 'statistics'])->name('advertisers.statistics');
    Route::get('/advertisers/payments', [AdvertiserController::class, 'payments'])->name('advertisers.payments');
    Route::get('/advertisers/settings', [AdvertiserController::class, 'settings'])->name('advertisers.settings');
});
