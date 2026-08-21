<?php

namespace App\Http\Controllers;

use App\Models\ArticleFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            // Only slugs that exist and are actually published. Without this
            // the table is an open endpoint anyone can write arbitrary
            // strings into.
            'slug' => ['required', 'string', Rule::exists('posts', 'slug')->where('status', 'published')],
            'helpful' => ['required', 'boolean'],
        ]);

        ArticleFeedback::create($data);

        return response()->json(['recorded' => true]);
    }
}
