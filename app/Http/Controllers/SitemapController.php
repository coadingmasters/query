<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public const CACHE_KEY = 'sitemap.xml';

    public const LAST_GENERATED_KEY = 'sitemap.last_generated';

    /**
     * The XML sitemap.
     *
     * Generated rather than kept as a static file so the URLs always follow
     * APP_URL and new pages can be added here as the site grows. Cached for
     * a day (rebuilding it is cheap, but there's no reason to redo it on
     * every crawler hit) — see admin.settings for the "regenerate now" and
     * "excluded paths" controls that invalidate/filter this.
     */
    public function __invoke(): Response
    {
        $xml = Cache::remember(self::CACHE_KEY, now()->addDay(), function () {
            Cache::forever(self::LAST_GENERATED_KEY, now());

            return view('sitemap', ['urls' => $this->urls()])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    private function urls(): array
    {
        $base = rtrim(config('app.url'), '/');

        // lastmod tells a crawler whether a re-fetch is worth it. It is taken
        // from each page's own view file rather than "now", because claiming
        // a page changed today when it did not trains crawlers to ignore it.
        $pages = [
            ['', 'views/home.blade.php', '1.0'],
            ['/about', 'views/about.blade.php', '0.7'],
            ['/how-it-works', 'views/how-it-works.blade.php', '0.6'],
            ['/author', 'views/author.blade.php', '0.6'],
            ['/tools', 'views/tools/index.blade.php', '0.8'],
            ['/tools/cat-age-calculator', 'views/tools/cat-age-calculator.blade.php', '0.9'],
            ['/tools/cat-pregnancy-calculator', 'views/tools/cat-pregnancy-calculator.blade.php', '0.9'],
            ['/tools/cat-calorie-calculator', 'views/tools/cat-calorie-calculator.blade.php', '0.9'],
            ['/tools/cat-vaccination-tracker', 'views/tools/cat-vaccination-tracker.blade.php', '0.9'],
            ['/tools/cat-weight-checker', 'views/tools/cat-weight-checker.blade.php', '0.9'],
            ['/tools/cat-name-generator', 'views/tools/cat-name-generator.blade.php', '0.9'],
            ['/food-guides', 'views/food-guides/index.blade.php', '0.8'],
            ['/blog', 'views/blog/index.blade.php', '0.8'],
            ['/shop', 'views/shop/index.blade.php', '0.7'],
            ['/faq', 'views/faq.blade.php', '0.8'],
            ['/contact', 'views/contact.blade.php', '0.5'],
            ['/terms', 'views/terms.blade.php', '0.3'],
            ['/privacy', 'views/privacy.blade.php', '0.3'],
        ];

        $excluded = $this->excludedPaths();

        $staticUrls = collect($pages)
            ->reject(fn (array $page) => in_array($page[0] ?: '/', $excluded, true))
            ->map(function (array $page) use ($base): array {
                [$path, $view, $priority] = $page;
                $file = resource_path($view);

                return [
                    'loc' => $base.($path ?: '/'),
                    'lastmod' => date('Y-m-d', is_file($file) ? filemtime($file) : time()),
                    'priority' => $priority,
                ];
            });

        $postUrls = Post::published()
            ->get(['slug', 'updated_at'])
            ->reject(fn ($post) => in_array('/blog/'.$post->slug, $excluded, true))
            ->map(fn ($post) => [
                'loc' => $base.'/blog/'.$post->slug,
                'lastmod' => $post->updated_at->format('Y-m-d'),
                'priority' => '0.8',
            ]);

        // Foods live in a config file rather than a table, so there is no
        // per-row updated_at — the file's own mtime stands in for all of them.
        $foodsLastmod = date('Y-m-d', filemtime(config_path('catalog.php')));
        $foodUrls = collect(config('catalog.foods'))
            ->reject(fn (array $food) => in_array('/food-guides/'.$food['slug'], $excluded, true))
            ->map(fn (array $food) => [
                'loc' => $base.'/food-guides/'.$food['slug'],
                'lastmod' => $foodsLastmod,
                'priority' => '0.6',
            ]);

        return $staticUrls->concat($postUrls)->concat($foodUrls)->all();
    }

    private function excludedPaths(): array
    {
        $raw = Setting::current()->sitemap_excluded_paths;

        if (! $raw) {
            return [];
        }

        return collect(preg_split('/\r?\n/', $raw))
            ->map(fn (string $path) => '/'.ltrim(trim($path), '/'))
            ->filter()
            ->all();
    }
}
