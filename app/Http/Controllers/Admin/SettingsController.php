<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SitemapController;
use App\Models\Author;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings', [
            'settings' => Setting::current(),
            'sitemapLastGenerated' => Cache::get(SitemapController::LAST_GENERATED_KEY),
        ]);
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
            'seo_site_name' => ['nullable', 'string', 'max:60'],
            'seo_og_image' => ['nullable', 'image', 'max:2048'],
            'remove_seo_og_image' => ['nullable', 'boolean'],
            'seo_twitter_card' => ['nullable', Rule::in(['summary', 'summary_large_image'])],
            'schema_org_logo' => ['nullable', 'image', 'max:2048'],
            'remove_schema_org_logo' => ['nullable', 'boolean'],
            'schema_facebook_url' => ['nullable', 'url', 'max:255'],
            'schema_instagram_url' => ['nullable', 'url', 'max:255'],
            'schema_twitter_url' => ['nullable', 'url', 'max:255'],
            'schema_youtube_url' => ['nullable', 'url', 'max:255'],
            'schema_pinterest_url' => ['nullable', 'url', 'max:255'],
            'robots_txt' => ['nullable', 'string', 'max:2000'],
            'sitemap_excluded_paths' => ['nullable', 'string', 'max:2000'],
        ]);

        $settings = Setting::current();

        $this->handleImage($request, $data, $settings, 'seo_og_image', 'remove_seo_og_image');
        $this->handleImage($request, $data, $settings, 'schema_org_logo', 'remove_schema_org_logo');
        unset($data['remove_seo_og_image'], $data['remove_schema_org_logo']);

        $settings->update($data);

        $this->syncFounderAuthor($data);

        return redirect()->route('admin.settings.index')->with('status', 'Settings updated.');
    }

    public function regenerateSitemap(): RedirectResponse
    {
        Cache::forget(SitemapController::CACHE_KEY);

        return redirect()->route('admin.settings.index')->with('status', 'Sitemap will regenerate on its next request.');
    }

    private function handleImage(Request $request, array &$data, Setting $settings, string $field, string $removeField): void
    {
        if ($request->hasFile($field)) {
            if ($settings->{$field}) {
                Storage::disk('public')->delete($settings->{$field});
            }

            $data[$field] = $request->file($field)->store('settings', 'public');
        } elseif ($request->boolean($removeField) && $settings->{$field}) {
            Storage::disk('public')->delete($settings->{$field});
            $data[$field] = null;
        } else {
            unset($data[$field]);
        }
    }

    /**
     * Keeps a matching Author row in sync so the founder can be picked as a
     * post's byline without re-entering their bio and socials — editing
     * still happens here, not on the Authors admin page.
     */
    private function syncFounderAuthor(array $data): void
    {
        $founder = Author::where('is_founder', true)->first();

        if (blank($data['author_name'] ?? null)) {
            $founder?->update(['is_active' => false]);

            return;
        }

        Author::updateOrCreate(
            ['is_founder' => true],
            [
                'name' => $data['author_name'],
                'credentials' => $data['author_role'] ?? null,
                'bio' => $data['author_bio'] ?? null,
                'twitter_url' => $data['author_twitter_url'] ?? null,
                'linkedin_url' => $data['author_linkedin_url'] ?? null,
                'website_url' => $data['author_website_url'] ?? null,
                'is_active' => true,
            ]
        );
    }
}
