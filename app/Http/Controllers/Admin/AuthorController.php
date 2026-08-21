<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Author::withCount('posts')->orderBy('name')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $author = Author::create($this->validated($request));

        return response()->json($author, 201);
    }

    public function update(Request $request, Author $author): JsonResponse
    {
        $author->update($this->validated($request, $author));

        return response()->json($author);
    }

    public function destroy(Author $author): JsonResponse
    {
        if ($author->posts()->exists()) {
            return response()->json(['message' => 'This author still has posts assigned to them.'], 422);
        }

        if ($author->photo) {
            Storage::disk('public')->delete($author->photo);
        }

        $author->delete();

        return response()->json(['message' => 'Author deleted.']);
    }

    private function validated(Request $request, ?Author $author = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('authors', 'slug')->ignore($author?->id)],
            'email' => ['nullable', 'email', 'max:190'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'credentials' => ['nullable', 'string', 'max:190'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            if ($author?->photo) {
                Storage::disk('public')->delete($author->photo);
            }

            $data['photo'] = $request->file('photo')->store('authors', 'public');
        } else {
            unset($data['photo']);
        }

        return $data;
    }
}
