<?php

namespace App\Console\Commands;

use App\Models\SearchConsoleUrl;
use App\Support\SearchConsole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SearchConsoleInspectUrls extends Command
{
    protected $signature = 'search-console:inspect {--limit=200}';

    protected $description = 'Check every sitemap URL\'s live indexing status via the Search Console API';

    public function handle(SearchConsole $console): int
    {
        $urls = $this->sitemapUrls();

        if ($urls->isEmpty()) {
            $this->error('Could not read the sitemap.');

            return self::FAILURE;
        }

        $limit = (int) $this->option('limit');
        $bar = $this->output->createProgressBar(min($urls->count(), $limit));

        foreach ($urls->take($limit) as $url) {
            try {
                $result = $console->inspect($url);

                SearchConsoleUrl::updateOrCreate(
                    ['url' => $url],
                    [...$result, 'checked_at' => now()]
                );
            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("  {$url}: {$e->getMessage()}");
            }

            // The API's per-minute quota is generous but not unlimited —
            // a small pause keeps a full sweep from ever tripping it.
            usleep(300_000);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return self::SUCCESS;
    }

    private function sitemapUrls(): \Illuminate\Support\Collection
    {
        $xml = Http::get(config('services.search_console.site_url').'sitemap.xml')->body();
        preg_match_all('#<loc>(.*?)</loc>#', $xml, $matches);

        return collect($matches[1] ?? []);
    }
}
