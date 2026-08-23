<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class MediaController extends Controller
{
    private const MAX_DIMENSION = 2400;

    private const QUALITY = 82;

    public function index(): View
    {
        $media = Media::latest()->get();

        return view('admin.media', [
            'media' => $media,
            'categories' => Media::CATEGORIES,
            'stats' => [
                'total' => $media->count(),
                'size' => $this->humanSize((int) $media->sum('size_bytes')),
                'byCategory' => collect(Media::CATEGORIES)
                    ->mapWithKeys(fn (string $category): array => [$category => $media->where('category', $category)->count()]),
            ],
        ]);
    }

    /**
     * Converts every uploaded file to WebP and stores it — the point of this
     * page: drop in whatever you have (PNG, JPEG, even another WebP) and it
     * comes out the other side as one optimized file with a name you chose.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'max:12288', 'mimes:jpeg,jpg,png,webp,gif,avif'],
            'name' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Media::CATEGORIES)],
        ]);

        $files = $request->file('images');
        $manager = new ImageManager(new Driver());
        $created = [];

        foreach ($files as $file) {
            // A custom name only makes sense for a single file — with several
            // at once every one would collide, so each keeps its own original
            // filename as its name instead.
            $baseName = (count($files) === 1 && filled($data['name'] ?? null))
                ? $data['name']
                : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $slug = Str::slug($baseName) ?: 'image';
            $path = 'media/'.$slug.'-'.Str::random(8).'.webp';

            $image = $manager->decodeSplFileInfo($file);
            $image->scaleDown(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION);
            $encoded = $image->encode(new WebpEncoder(quality: self::QUALITY));

            Storage::disk('public')->put($path, (string) $encoded);

            $created[] = Media::create([
                'name' => $baseName,
                'path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => 'image/webp',
                'width' => $image->width(),
                'height' => $image->height(),
                'size_bytes' => $encoded->size(),
                'alt_text' => $data['alt_text'] ?? null,
                'category' => $data['category'],
            ]);
        }

        return response()->json(['media' => $created], 201);
    }

    public function update(Request $request, Media $media): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Media::CATEGORIES)],
        ]);

        $media->update($data);

        return response()->json($media);
    }

    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json(['message' => 'Image deleted.']);
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1024 ** 2) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1024 ** 2, 1).' MB';
    }
}
