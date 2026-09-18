<?php

namespace App\Console\Commands;

use App\Models\SearchConsoleMetric;
use App\Support\SearchConsole;
use Illuminate\Console\Command;

class SearchConsoleSyncPerformance extends Command
{
    protected $signature = 'search-console:sync-performance {--days=30}';

    protected $description = 'Pull daily clicks/impressions/position from Search Console';

    public function handle(SearchConsole $console): int
    {
        $days = (int) $this->option('days');

        // Search Console data lags roughly two days behind real time, so the
        // trailing window naturally excludes the days it has nothing for yet.
        $rows = $console->performanceByDate(
            now()->subDays($days + 2)->toDateString(),
            now()->subDays(2)->toDateString(),
        );

        foreach ($rows as $row) {
            SearchConsoleMetric::updateOrCreate(['date' => $row['date']], $row);
        }

        $this->info(count($rows).' days synced.');

        return self::SUCCESS;
    }
}
