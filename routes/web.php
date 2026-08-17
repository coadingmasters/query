<?php

use App\Http\Controllers\ComingSoonController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', ComingSoonController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
