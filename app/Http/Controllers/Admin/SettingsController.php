<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings', ['settings' => Setting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'brand_email' => ['nullable', 'email', 'max:190'],
            'brand_tagline' => ['nullable', 'string', 'max:190'],
            'brand_description' => ['nullable', 'string', 'max:500'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'author_role' => ['nullable', 'string', 'max:190'],
            'author_tagline' => ['nullable', 'string', 'max:190'],
            'author_bio' => ['nullable', 'string', 'max:4000'],
            'author_linkedin_url' => ['nullable', 'url', 'max:255'],
            'author_twitter_url' => ['nullable', 'url', 'max:255'],
            'author_github_url' => ['nullable', 'url', 'max:255'],
            'author_website_url' => ['nullable', 'url', 'max:255'],
            'reviewer_name' => ['nullable', 'string', 'max:120'],
            'reviewer_credentials' => ['nullable', 'string', 'max:120'],
            'reviewer_reviewed_on' => ['nullable', 'date'],
            'reviewer_profile_url' => ['nullable', 'url', 'max:255'],
            'legal_jurisdiction' => ['nullable', 'string', 'max:190'],
        ]);

        Setting::current()->update($data);

        return redirect()->route('admin.settings.index')->with('status', 'Settings updated.');
    }
}
