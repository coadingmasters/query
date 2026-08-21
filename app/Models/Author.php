<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'email', 'bio', 'photo', 'credentials',
        'twitter_url', 'linkedin_url', 'website_url', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    protected static function booted(): void
    {
        static::saving(function (Author $author) {
            if (! $author->slug) {
                $author->slug = Str::slug($author->name);
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
