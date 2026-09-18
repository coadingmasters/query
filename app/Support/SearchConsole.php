<?php

namespace App\Support;

use Google\Client;
use Google\Service\SearchConsole as GoogleSearchConsole;
use Google\Service\SearchConsole\InspectUrlIndexRequest;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;

class SearchConsole
{
    private GoogleSearchConsole $service;

    private string $siteUrl;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(config('services.search_console.credentials'));
        $client->addScope('https://www.googleapis.com/auth/webmasters.readonly');

        $this->service = new GoogleSearchConsole($client);
        $this->siteUrl = config('services.search_console.site_url');
    }

    /** Live indexing status for one URL. */
    public function inspect(string $url): array
    {
        $request = new InspectUrlIndexRequest();
        $request->setInspectionUrl($url);
        $request->setSiteUrl($this->siteUrl);

        $result = $this->service->urlInspection_index->inspect($request)
            ->getInspectionResult()->getIndexStatusResult();

        return [
            'coverage_state' => $result->getCoverageState(),
            'verdict' => $result->getVerdict(),
            'indexing_state' => $result->getIndexingState(),
            'robots_txt_state' => $result->getRobotsTxtState(),
            'last_crawl_time' => $result->getLastCrawlTime() ?: null,
        ];
    }

    /** Daily clicks/impressions/ctr/position for the given date range. */
    public function performanceByDate(string $start, string $end): array
    {
        $request = new SearchAnalyticsQueryRequest();
        $request->setStartDate($start);
        $request->setEndDate($end);
        $request->setDimensions(['date']);
        $request->setRowLimit(1000);

        $rows = $this->service->searchanalytics->query($this->siteUrl, $request)->getRows() ?? [];

        return array_map(fn ($row) => [
            'date' => $row->getKeys()[0],
            'clicks' => (int) $row->getClicks(),
            'impressions' => (int) $row->getImpressions(),
            'ctr' => (float) $row->getCtr(),
            'position' => (float) $row->getPosition(),
        ], $rows);
    }
}
