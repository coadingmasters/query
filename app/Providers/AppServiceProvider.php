<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $this->configureSettingsOverride();
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
     * Apply strict Eloquent behavior outside production.
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

    /**
     * Let admin-edited settings override the config/env values everywhere
     * they're already read (Schema.php, the footer, meta tags, the contact
     * form, the about/author pages), without touching any of those call
     * sites. A null column leaves the original config() value untouched —
     * the same "graceful when unset" fallback config/author.php already
     * documents, just with an admin-editable layer in front of it.
     *
     * Skipped in console: artisan commands (including the very first
     * `migrate` that creates this table) don't need it, and running it
     * there would query a table that may not exist yet.
     */
    protected function configureSettingsOverride(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        if (! Schema::hasTable('settings')) {
            return;
        }

        $settings = Setting::query()->first();

        if (! $settings) {
            return;
        }

        $map = [
            'brand.email' => $settings->brand_email,
            'brand.tagline' => $settings->brand_tagline,
            'brand.description' => $settings->brand_description,
            'author.founder.name' => $settings->author_name,
            'author.founder.role' => $settings->author_role,
            'author.founder.tagline' => $settings->author_tagline,
            'author.reviewer.name' => $settings->reviewer_name,
            'author.reviewer.credentials' => $settings->reviewer_credentials,
            'author.reviewer.reviewed_on' => $settings->reviewer_reviewed_on?->toDateString(),
            'author.reviewer.profile' => $settings->reviewer_profile_url,
            'legal.jurisdiction' => $settings->legal_jurisdiction,
            'app.name' => $settings->seo_site_name,
            'brand.og_image' => $settings->seo_og_image_url,
            'brand.twitter_card' => $settings->seo_twitter_card,
            'brand.schema_logo' => $settings->schema_org_logo_url,
        ];

        foreach (array_filter($map, fn ($value) => filled($value)) as $key => $value) {
            config([$key => $value]);
        }

        if (filled($settings->author_bio)) {
            config(['author.founder.bio' => preg_split('/\n{2,}/', trim($settings->author_bio))]);
        }

        $profiles = array_values(array_filter([
            $settings->author_linkedin_url,
            $settings->author_twitter_url,
            $settings->author_github_url,
            $settings->author_website_url,
        ]));

        if ($profiles) {
            config(['author.founder.profiles' => $profiles]);
        }

        $orgProfiles = array_values(array_filter([
            $settings->schema_facebook_url,
            $settings->schema_instagram_url,
            $settings->schema_twitter_url,
            $settings->schema_youtube_url,
            $settings->schema_pinterest_url,
        ]));

        if ($orgProfiles) {
            config(['brand.social' => $orgProfiles]);
        }
    }
}
