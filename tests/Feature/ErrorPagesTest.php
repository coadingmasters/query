<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Before this, a broken link or a real server error fell through to
 * Laravel's own default page — unbranded, and with nothing pointing a lost
 * visitor back toward the site. Google Search Console also flags an
 * indexable 404 as a soft-404 problem, so every one of these has to carry
 * noindex on top of looking like the rest of the site.
 */
class ErrorPagesTest extends TestCase
{
    public function test_a_missing_page_renders_the_branded_404(): void
    {
        $response = $this->get('/this-page-does-not-exist-at-all');

        $response->assertNotFound()
            ->assertSee('That page has wandered off')
            ->assertSee('PurrQuery', false);
    }

    public function test_the_404_is_not_indexable(): void
    {
        $response = $this->get('/this-page-does-not-exist-at-all');

        $response->assertSee('name="robots" content="noindex, nofollow"', false);
        $this->assertSame('noindex, nofollow', $response->headers->get('X-Robots-Tag'));
    }

    public function test_the_404_offers_a_way_back_in(): void
    {
        $html = $this->get('/this-page-does-not-exist-at-all')->getContent();

        $this->assertStringContainsString('href="/"', $html);
        $this->assertStringContainsString('action="/search"', $html);
    }

    public function test_every_error_view_renders_without_a_real_request(): void
    {
        foreach (['403', '419', '429', '500', '503'] as $code) {
            $html = view("errors.{$code}")->render();

            $this->assertStringContainsString('PurrQuery', $html, "errors.{$code} is missing the brand name");
            $this->assertStringContainsString('noindex', $html, "errors.{$code} is indexable");
        }
    }

    /** An ordinary page must stay indexable — this middleware change should only ever touch error responses. */
    public function test_an_ordinary_page_still_reports_indexable(): void
    {
        $response = $this->get('/');

        $this->assertSame(
            'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            $response->headers->get('X-Robots-Tag')
        );
    }
}
