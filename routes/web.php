<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\ArticleFeedbackController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Tools\CatAgeCalculatorController;
use App\Http\Controllers\Tools\CatPregnancyCalculatorController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');

// The author page carries its own slug rather than an anchor on /about:
// authorship is a claim about a person, and it reads as one when it has a
// page of its own.
Route::get('/author', AuthorController::class)->name('author');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->name('blog.show');

// Rate limited because it writes to the database from an unauthenticated page.
Route::post('/blog/feedback', [ArticleFeedbackController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('blog.feedback');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');

// Rate limited: it writes to the database from an unauthenticated form.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/tools/cat-age-calculator', CatAgeCalculatorController::class)
    ->name('tools.cat-age-calculator');

Route::get('/tools/cat-pregnancy-calculator', CatPregnancyCalculatorController::class)
    ->name('tools.cat-pregnancy-calculator');

Route::get('/faq', FaqController::class)->name('faq');

Route::get('/terms', TermsController::class)->name('terms');
Route::get('/privacy', PrivacyController::class)->name('privacy');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/site.webmanifest', ManifestController::class)->name('manifest');

// Rate limited because it writes to the database from an unauthenticated form.
Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('subscribe');

// Admin: no public link points here, and every route below carries noindex.
Route::prefix('admin')->middleware('noindex')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    Route::get('/dashboard', DashboardController::class)
        ->middleware('auth')
        ->name('dashboard');

    Route::get('/messages', [MessagesController::class, 'index'])
        ->middleware('auth')
        ->name('messages.index');
    Route::post('/messages/{message}/handled', [MessagesController::class, 'markHandled'])
        ->middleware('auth')
        ->name('messages.handled');

    Route::get('/subscribers', [SubscribersController::class, 'index'])
        ->middleware('auth')
        ->name('subscribers.index');

    Route::get('/feedback', [FeedbackController::class, 'index'])
        ->middleware('auth')
        ->name('feedback.index');
});
