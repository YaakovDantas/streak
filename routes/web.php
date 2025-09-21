<?php

use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\ClickButtonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebhookAbacatePayController;
use App\Http\Controllers\WebhookStripeController;
use App\Http\Controllers\RefillAdController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/click', ClickButtonController::class)->name('click');

    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/refill/ad', RefillAdController::class)->name('refill.ad');
});

Route::get('/auth/{provider}', [SocialController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('auth.callback');


Route::post('/webhook/stripe', WebhookStripeController::class)->name('webhook.stripe');
Route::post('/webhook/abacatepay', WebhookAbacatePayController::class)->name('webhook.abacatepay');


require __DIR__.'/auth.php';
