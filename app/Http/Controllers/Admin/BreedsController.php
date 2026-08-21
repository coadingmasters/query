<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BreedsController extends Controller
{
    public function index(): View
    {
        // Filtering happens live in the browser (68 rows is nothing to send
        // down and filter client-side), so the query here is unconditional;
        // it exists to give the page something real to search rather than
        // to do the searching itself.
        $breeds = Breed::query()->orderBy('popularity_rank')->get();

        $counts = [
            'total' => Breed::count(),
            'small' => Breed::where('size_category', 'small')->count(),
            'medium' => Breed::where('size_category', 'medium')->count(),
            'large' => Breed::where('size_category', 'large')->count(),
        ];

        $sizeMax = max($counts['small'], $counts['medium'], $counts['large'], 1);

        return view('admin.breeds.index', [
            'breeds' => $breeds,
            'counts' => $counts,
            'sizeChart' => [
                ['label' => 'Small', 'count' => $counts['small'], 'percent' => (int) round($counts['small'] / $sizeMax * 100)],
                ['label' => 'Medium', 'count' => $counts['medium'], 'percent' => (int) round($counts['medium'] / $sizeMax * 100)],
                ['label' => 'Large', 'count' => $counts['large'], 'percent' => (int) round($counts['large'] / $sizeMax * 100)],
            ],
            'registryChart' => $this->registryChart(),
        ]);
    }

    /** How many breeds each major registry recognizes, out of what is seeded here. */
    private function registryChart(): array
    {
        $rows = [
            'TICA' => Breed::where('registry_tica', true)->count(),
            'CFA' => Breed::where('registry_cfa', true)->count(),
            'FIFe' => Breed::where('registry_fife', true)->count(),
        ];

        $max = max(max($rows), 1);

        return collect($rows)->map(fn ($count, $label) => [
            'label' => $label,
            'count' => $count,
            'percent' => (int) round($count / $max * 100),
        ])->values()->all();
    }

    public function create(): View
    {
        return view('admin.breeds.form', ['breed' => new Breed]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $breed = Breed::create($data);

        return redirect()->route('admin.breeds.edit', $breed)->with('status', 'Breed added.');
    }

    public function edit(Breed $breed): View
    {
        return view('admin.breeds.form', compact('breed'));
    }

    public function update(Request $request, Breed $breed): RedirectResponse
    {
        $data = $this->validated($request, $breed);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $breed->update($data);

        return redirect()->route('admin.breeds.edit', $breed)->with('status', 'Breed updated.');
    }

    public function destroy(Breed $breed): RedirectResponse
    {
        $breed->delete();

        return redirect()->route('admin.breeds.index')->with('status', 'Breed removed.');
    }

    private function validated(Request $request, ?Breed $breed = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('breeds', 'slug')->ignore($breed?->id)],
            'origin_country' => ['nullable', 'string', 'max:120'],
            'registry_tica' => ['boolean'],
            'registry_cfa' => ['boolean'],
            'registry_fife' => ['boolean'],
            'size_category' => ['required', Rule::in(['small', 'medium', 'large'])],
            'weight_min_kg' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'weight_max_kg' => ['nullable', 'numeric', 'min:0', 'max:99.9', 'gte:weight_min_kg'],
            'coat_length' => ['nullable', Rule::in(['hairless', 'short', 'medium', 'long'])],
            'energy_level' => ['nullable', 'integer', 'between:1,5'],
            'affection_level' => ['nullable', 'integer', 'between:1,5'],
            'child_friendly' => ['nullable', 'integer', 'between:1,5'],
            'grooming_needs' => ['nullable', 'integer', 'between:1,5'],
            'shedding_level' => ['nullable', 'integer', 'between:1,5'],
            'intelligence' => ['nullable', 'integer', 'between:1,5'],
            'lifespan_min' => ['nullable', 'integer', 'min:0', 'max:40'],
            'lifespan_max' => ['nullable', 'integer', 'min:0', 'max:40', 'gte:lifespan_min'],
            'health_watch' => ['nullable', 'string'],
            'hypoallergenic' => ['boolean'],
            'good_for_apartments' => ['boolean'],
            'good_for_beginners' => ['boolean'],
            'description' => ['nullable', 'string', 'max:2000'],
            'temperament_summary' => ['nullable', 'string', 'max:255'],
            'fun_fact' => ['nullable', 'string', 'max:1000'],
            'popularity_rank' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        // Checkboxes that are left unchecked never reach the request at all,
        // so each boolean is set explicitly rather than trusted to validate().
        foreach (['registry_tica', 'registry_cfa', 'registry_fife', 'hypoallergenic', 'good_for_apartments', 'good_for_beginners', 'is_active'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        // One condition per line in the textarea, stored as a JSON array to
        // match the format the seeder writes.
        $data['health_watch'] = $request->filled('health_watch')
            ? array_values(array_filter(array_map('trim', explode("\n", $request->string('health_watch')))))
            : null;

        return $data;
    }
}
