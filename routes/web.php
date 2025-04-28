<?php

use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
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
    Route::get('/advertisers/payments', [PaymentController::class, 'index'])->name('advertisers.payments.list');
    Route::get('/advertisers/settings', [AdvertiserController::class, 'settings'])->name('advertisers.settings');
    Route::get('/advertisers/topup', [PaymentController::class, 'index'])->name('advertisers.payment.form');
    Route::post('/advertisers/topup/checkout', [PaymentController::class, 'checkout'])->name('advertisers.payment.checkout');
    Route::get('/advertisers/topup/success', [PaymentController::class, 'success'])->name('advertisers.payment.success');
    Route::get('/advertisers/topup/failed', [PaymentController::class, 'failed'])->name('advertisers.payment.failed');
});

