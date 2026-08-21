<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'quick_answer', 'content', 'sources',
        'featured_image', 'featured_image_alt', 'featured_image_width', 'featured_image_height',
        'author_id', 'category_id', 'status', 'is_featured',
        'meta_title', 'meta_description', 'canonical_url', 'published_at',
        'reading_time', 'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'sources' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (! $post->slug) {
                $post->slug = Str::slug($post->title);
            }

            $post->reading_time = max(1, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 200));

            // Read once, from whichever file actually ends up on disk, rather
            // than trusted to whoever uploaded it. Every template that prints
            // the featured image can then emit width/height for free, which
            // is what keeps Cumulative Layout Shift at zero without a
            // filesystem read on every page view.
            if ($post->isDirty('featured_image')) {
                $size = $post->featured_image
                    ? @getimagesize(Storage::disk('public')->path($post->featured_image))
                    : false;

                $post->featured_image_width = $size ? $size[0] : null;
                $post->featured_image_height = $size ? $size[1] : null;
            }
        });
    }

    /** Null once there is no file, rather than a broken src on the page. */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? Storage::url($this->featured_image) : null;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PostTag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(PostFaq::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(fn (Builder $q) => $q
            ->where('title', 'like', "%{$term}%")
            ->orWhere('excerpt', 'like', "%{$term}%"));
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'published' => ['label' => 'Published', 'color' => 'green'],
            'scheduled' => ['label' => 'Scheduled', 'color' => 'amber'],
            default => ['label' => 'Draft', 'color' => 'gray'],
        };
    }

    public function getReadingTimeTextAttribute(): string
    {
        return $this->reading_time.' min read';
    }
}
