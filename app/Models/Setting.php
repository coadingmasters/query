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
    ];

    protected $casts = [
        'reviewer_reviewed_on' => 'date',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create();
    }
}
