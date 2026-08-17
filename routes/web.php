<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');

// Rate limited: it writes to the database from an unauthenticated form.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/site.webmanifest', ManifestController::class)->name('manifest');

// Rate limited because it writes to the database from an unauthenticated form.
Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('subscribe');
