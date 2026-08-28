<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\NameGeneratorSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatNameGeneratorSaveController extends Controller
{
    /**
     * Records one save toward a name's real trending count. The client
     * calls this once per browser per name — the first time it is favorited,
     * not on every click — so the count reflects distinct interest rather
     * than one visitor inflating a single name.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Matched against the generator's own list rather than trusted
            // freeform: this table backs a public "most saved" ranking, and
            // an unvalidated name field would let it be seeded with junk.
            'name' => ['required', 'string', 'in:'.collect(config('cat-name-generator.names'))
                ->pluck('name')->implode(',')],
        ]);

        NameGeneratorSave::query()->firstOrCreate(
            ['name' => $validated['name']],
            ['save_count' => 0],
        )->increment('save_count');

        return response()->json(['ok' => true]);
    }
}
