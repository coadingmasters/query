<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    public const CATEGORIES = ['breeds', 'blog', 'tools', 'team', 'general'];

    protected $fillable = [
        'name', 'path', 'original_filename', 'mime_type', 'size_bytes', 'description', 'category',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    protected $appends = ['url'];

    protected static function booted(): void
    {
        static::deleting(fn (Video $video) => Storage::disk('public')->delete($video->path));
    }

    public function getUrlAttribute(): string
    {
        return '/storage/'.$this->path;
    }
}
