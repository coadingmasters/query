<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Redirect extends Model
{
    public const CACHE_KEY = 'redirects.active-map';

    protected $fillable = ['from_path', 'to_path', 'status_code', 'is_active'];

    protected $casts = [
        'status_code' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Redirect $redirect) {
            $redirect->from_path = '/'.ltrim(trim($redirect->from_path), '/');
            $redirect->to_path = Str::startsWith($redirect->to_path, ['http://', 'https://'])
                ? $redirect->to_path
                : '/'.ltrim(trim($redirect->to_path), '/');
        });

        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** path => [to_path, status_code], cached since HandleRedirects checks it on every request. */
    public static function activeMap(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::where('is_active', true)
            ->get()
            ->mapWithKeys(fn (Redirect $r) => [$r->from_path => [$r->to_path, $r->status_code]])
            ->all());
    }
}
