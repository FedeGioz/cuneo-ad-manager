<?php

use App\Http\Controllers\AdServeController;
use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/category/{name}', [GuestController::class, 'category'])->name('category');
Route::get('/category-ads', [GuestController::class, 'categoryAjax'])->name('category.ads');
Route::get('/redirect', [AdServeController::class, 'redirect'])->name('redirect');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [AdvertiserController::class, 'index'])->name('advertisers.index');
    Route::get('/advertisers/create', [AdvertiserController::class, 'showCreateCampaign'])->name('advertisers.campaigns.showCreate');
    Route::post('/advertisers/create', [AdvertiserController::class, 'createCampaign'])->name('advertisers.campaigns.create');
    Route::get('/advertisers/statistics', [AdvertiserController::class, 'statistics'])->name('advertisers.statistics');
    Route::get('/advertisers/payments', [PaymentController::class, 'index'])->name('advertisers.payments.list');
    Route::get('/advertisers/settings', [AdvertiserController::class, 'settings'])->name('advertisers.settings');
    Route::get('/advertisers/topup', [PaymentController::class, 'index'])->name('advertisers.payment.form');
    Route::post('/advertisers/topup/checkout', [PaymentController::class, 'checkout'])->name('advertisers.payment.checkout');
    Route::get('/advertisers/topup/success', [PaymentController::class, 'success'])->name('advertisers.payment.success');
    Route::get('/advertisers/topup/failed', [PaymentController::class, 'failed'])->name('advertisers.payment.failed');
    Route::put('/advertisers/settings/update', [AdvertiserController::class, 'updateSettings'])->name('advertisers.settings.update');
});

Route::get('device-info', [AdServeController::class, 'device_info']);
Route::get('match', [AdServeController::class, 'match']);
Route::get('start-campaign', [AdvertiserController::class, 'startCampaign'])->name('advertisers.campaigns.start');
Route::get('pause-campaign', [AdvertiserController::class, 'pauseCampaign'])->name('advertisers.campaigns.pause');
Route::get('delete-campaign', [AdvertiserController::class, 'deleteCampaign'])->name('advertisers.campaigns.delete');
