<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    public const CATEGORIES = ['breeds', 'blog', 'tools', 'team', 'general'];

    protected $fillable = [
        'name', 'path', 'original_filename', 'mime_type', 'width', 'height', 'size_bytes', 'alt_text', 'category',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size_bytes' => 'integer',
    ];

    protected static function booted(): void
    {
        // The row is the easy part to lose track of; the file left behind on
        // disk with nothing pointing at it is the one that actually costs
        // storage, silently, forever.
        static::deleting(fn (Media $media) => Storage::disk('public')->delete($media->path));
    }

    // Root-relative rather than Storage::url()'s absolute form, matching
    // every other upload accessor in the app (Setting, Post).
    public function getUrlAttribute(): string
    {
        return '/storage/'.$this->path;
    }
}
