<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\BreedsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostTagController;
use App\Http\Controllers\Admin\SettingsController;
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
use App\Http\Controllers\Tools\CatCalorieCalculatorController;
use App\Http\Controllers\Tools\CatPregnancyCalculatorController;
use App\Http\Controllers\Tools\VaccinationTrackerController;
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

Route::get('/tools/cat-calorie-calculator', CatCalorieCalculatorController::class)
    ->name('tools.cat-calorie-calculator');

Route::get('/tools/cat-vaccination-tracker', VaccinationTrackerController::class)
    ->name('tools.cat-vaccination-tracker');

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

    Route::middleware('auth')->prefix('settings')->name('settings.')->group(function (): void {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
    });

    Route::middleware('auth')->prefix('breeds')->name('breeds.')->group(function (): void {
        Route::get('/', [BreedsController::class, 'index'])->name('index');
        Route::get('/create', [BreedsController::class, 'create'])->name('create');
        Route::post('/', [BreedsController::class, 'store'])->name('store');
        Route::get('/{breed}/edit', [BreedsController::class, 'edit'])->name('edit');
        Route::put('/{breed}', [BreedsController::class, 'update'])->name('update');
        Route::delete('/{breed}', [BreedsController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('auth')->group(function (): void {
        Route::resource('posts', PostController::class);
        Route::post('posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
        Route::post('posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
        Route::post('posts/{post}/status', [PostController::class, 'updateStatus'])->name('posts.update-status');
        Route::post('posts/{post}/duplicate', [PostController::class, 'duplicate'])->name('posts.duplicate');

        Route::apiResource('post-categories', PostCategoryController::class);
        Route::apiResource('post-tags', PostTagController::class);
        Route::get('post-tags-search', [PostTagController::class, 'search'])->name('post-tags.search');
        Route::apiResource('authors', AdminAuthorController::class);
    });
});
