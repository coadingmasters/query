<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A single row of admin-editable site settings. Every column is nullable
 * and, when null, the site falls back to the existing config/env value —
 * see AppServiceProvider::configureSettingsOverride().
 */
class Setting extends Model
{
    protected $fillable = [
        'brand_email', 'brand_tagline', 'brand_description',
        'author_name', 'author_role', 'author_tagline', 'author_bio',
        'author_linkedin_url', 'author_twitter_url', 'author_github_url', 'author_website_url',
        'reviewer_name', 'reviewer_credentials', 'reviewer_reviewed_on', 'reviewer_profile_url',
        'legal_jurisdiction',
        'seo_site_name', 'seo_og_image', 'seo_twitter_card',
        'schema_org_logo', 'schema_facebook_url', 'schema_instagram_url', 'schema_twitter_url', 'schema_youtube_url',
        'robots_txt', 'sitemap_excluded_paths',
    ];

    protected $casts = [
        'reviewer_reviewed_on' => 'date',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create();
    }

    /**
     * A root-relative path ("/storage/...") rather than Storage::url()'s
     * full absolute URL — the config keys these feed (brand.og_image,
     * brand.schema_logo) default to a relative path ("/og-image.png") that
     * every reader already prepends the app URL to. Keeping both forms
     * relative avoids the domain getting prepended twice for an upload.
     */
    public function getSeoOgImageUrlAttribute(): ?string
    {
        return $this->seo_og_image ? '/storage/'.$this->seo_og_image : null;
    }

    public function getSchemaOrgLogoUrlAttribute(): ?string
    {
        return $this->schema_org_logo ? '/storage/'.$this->schema_org_logo : null;
    }
}
