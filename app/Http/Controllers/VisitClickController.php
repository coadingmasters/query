<?php

namespace App\Http\Controllers;

use App\Models\ClickEvent;
use App\Support\VisitorIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitClickController extends Controller
{
    public function store(Request $request, VisitorIdentity $visitors): JsonResponse
    {
        $data = $request->validate([
            'path' => ['required', 'string', 'max:2048'],
            'selector' => ['nullable', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'href' => ['nullable', 'string', 'max:2048'],
        ]);

        $token = $visitors->tokenFor($request);
        $visitor = $visitors->resolve($request, $token);

        ClickEvent::create([
            'visitor_id' => $visitor->id,
            'path' => $data['path'],
            'selector' => $data['selector'] ?? null,
            'label' => $data['label'] ?? null,
            'href' => $data['href'] ?? null,
            'created_at' => now(),
        ]);

        return response()->json(status: 204);
    }
}
