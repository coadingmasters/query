<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostCategoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $categories = PostCategory::withCount('posts')->orderBy('sort_order')->get();

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return view('admin.posts.categories', ['categories' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $category = PostCategory::create($data);

        return response()->json($category, 201);
    }

    public function update(Request $request, PostCategory $post_category): JsonResponse
    {
        $post_category->update($this->validated($request, $post_category));

        return response()->json($post_category);
    }

    public function destroy(PostCategory $post_category): JsonResponse
    {
        if ($post_category->posts()->exists()) {
            return response()->json(['message' => 'This category still has posts assigned to it.'], 422);
        }

        $post_category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }

    private function validated(Request $request, ?PostCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('post_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
