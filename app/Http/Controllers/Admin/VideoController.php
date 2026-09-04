<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VideoController extends Controller
{
    /**
     * No conversion happens here, unlike the image library: there's no
     * transcoder available on this host, so a video is stored exactly as
     * uploaded. The mime restriction below is what keeps every file in this
     * library actually playable in a browser without one.
     */
    private const MAX_KILOBYTES = 204800; // 200 MB

    public function index(): View
    {
        $videos = Video::latest()->get();

        return view('admin.videos', [
            'videos' => $videos,
            'categories' => Video::CATEGORIES,
            'stats' => [
                'total' => $videos->count(),
                'size' => $this->humanSize((int) $videos->sum('size_bytes')),
                'byCategory' => collect(Video::CATEGORIES)
                    ->mapWithKeys(fn (string $category): array => [$category => $videos->where('category', $category)->count()]),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm', 'max:'.self::MAX_KILOBYTES],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Video::CATEGORIES)],
        ]);

        $file = $request->file('video');
        $baseName = filled($data['name'] ?? null)
            ? $data['name']
            : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $slug = Str::slug($baseName) ?: 'video';
        $extension = $file->getClientOriginalExtension() ?: 'mp4';
        $path = $file->storeAs('videos', $slug.'-'.Str::random(8).'.'.$extension, 'public');

        $video = Video::create([
            'name' => $baseName,
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
        ]);

        return response()->json(['video' => $video], 201);
    }

    public function update(Request $request, Video $video): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Video::CATEGORIES)],
        ]);

        $video->update($data);

        return response()->json($video);
    }

    public function destroy(Video $video): JsonResponse
    {
        $video->delete();

        return response()->json(['message' => 'Video deleted.']);
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1024 ** 2) {
            return round($bytes / 1024, 1).' KB';
        }

        if ($bytes < 1024 ** 3) {
            return round($bytes / 1024 ** 2, 1).' MB';
        }

        return round($bytes / 1024 ** 3, 2).' GB';
    }
}
