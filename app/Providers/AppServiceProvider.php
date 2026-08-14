<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureUrls();
        $this->configureModels();
        $this->configureCommands();
    }

    /**
     * Force every generated URL to use HTTPS in production.
     *
     * Canonical tags, sitemap entries and feed links are all built from these
     * URLs. Emitting http:// versions alongside https:// ones would present
     * search engines with two URLs for the same page.
     */
    protected function configureUrls(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }

    /**
     * Apply strict Eloquent behaviour outside production.
     *
     * Surfaces lazy-loading (N+1 queries) and typo'd attribute access during
     * development, where slow pages are cheap to fix, rather than in
     * production where they would hurt page speed and rankings.
     */
    protected function configureModels(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::unguard(false);
    }

    /**
     * Block destructive database commands in production.
     *
     * Prevents an accidental `migrate:fresh` or `db:wipe` from destroying
     * live content.
     */
    protected function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
