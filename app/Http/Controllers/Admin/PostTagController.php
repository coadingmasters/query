<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostTagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            PostTag::withCount('posts')->orderBy('name')->get()
        );
    }

    public function search(Request $request): JsonResponse
    {
        $term = (string) $request->query('q', '');

        return response()->json(
            PostTag::query()
                ->when($term, fn ($q) => $q->where('name', 'like', "%{$term}%"))
                ->orderBy('name')
                ->limit(10)
                ->get(['id', 'name', 'slug', 'color'])
        );
    }

    public function store(Request $request): JsonResponse
    {
        $tag = PostTag::create($this->validated($request));

        return response()->json($tag, 201);
    }

    public function update(Request $request, PostTag $post_tag): JsonResponse
    {
        $post_tag->update($this->validated($request, $post_tag));

        return response()->json($post_tag);
    }

    public function destroy(PostTag $post_tag): JsonResponse
    {
        $post_tag->posts()->detach();
        $post_tag->delete();

        return response()->json(['message' => 'Tag deleted.']);
    }

    private function validated(Request $request, ?PostTag $tag = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('post_tags', 'name')->ignore($tag?->id)],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
    }
}
