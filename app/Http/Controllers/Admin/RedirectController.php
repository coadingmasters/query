<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RedirectController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $redirects = Redirect::orderByDesc('created_at')->get();

        if ($request->wantsJson()) {
            return response()->json($redirects);
        }

        return view('admin.redirects', ['redirects' => $redirects]);
    }

    public function store(Request $request): JsonResponse
    {
        $redirect = Redirect::create($this->validated($request));

        return response()->json($redirect, 201);
    }

    public function update(Request $request, Redirect $redirect): JsonResponse
    {
        $redirect->update($this->validated($request, $redirect));

        return response()->json($redirect);
    }

    public function destroy(Redirect $redirect): JsonResponse
    {
        $redirect->delete();

        return response()->json(['message' => 'Redirect deleted.']);
    }

    private function validated(Request $request, ?Redirect $redirect = null): array
    {
        $data = $request->validate([
            'from_path' => ['required', 'string', 'max:255', Rule::unique('redirects', 'from_path')->ignore($redirect?->id)],
            'to_path' => ['required', 'string', 'max:255'],
            'status_code' => ['required', Rule::in([301, 302])],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ('/'.ltrim(trim($data['from_path']), '/') === '/'.ltrim(trim($data['to_path']), '/')) {
            abort(422, 'A redirect can\'t point at itself.');
        }

        return $data;
    }
}
