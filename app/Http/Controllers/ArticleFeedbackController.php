<?php

namespace App\Http\Controllers;

use App\Models\ArticleFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            // Only slugs that exist. Without this the table is an open
            // endpoint anyone can write arbitrary strings into.
            'slug' => ['required', 'string', 'in:'.implode(',', array_keys(config('blog')))],
            'helpful' => ['required', 'boolean'],
        ]);

        ArticleFeedback::create($data);

        return response()->json(['recorded' => true]);
    }
}
