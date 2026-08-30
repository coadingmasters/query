<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The audience is American, so the copy has to read as American.
 *
 * These run against rendered HTML rather than the source files, because that
 * is what a reader and a crawler actually get. Test and build-script comments
 * are deliberately out of scope: nobody ships those.
 */
class UsEnglishTest extends TestCase
{
    /** Every page a visitor can reach. */
    public static function pages(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'how it works' => ['/how-it-works'],
            'author' => ['/author'],
            'contact' => ['/contact'],
            'faq' => ['/faq'],
            'terms' => ['/terms'],
            'privacy' => ['/privacy'],
            'blog index' => ['/blog'],
            'blog article' => ['/blog/why-do-cats-knead'],
            'sneezing article' => ['/blog/why-is-my-cat-sneezing'],
            'signs article' => ['/blog/signs-your-cat-is-sick'],
            'feeding article' => ['/blog/how-much-should-i-feed-my-cat'],
            'broccoli article' => ['/blog/can-cats-eat-broccoli'],
            'indoor food article' => ['/blog/best-food-for-indoor-cats'],
            'chicken article' => ['/blog/can-cats-eat-chicken'],
            'tuna article' => ['/blog/can-cats-eat-tuna'],
            'eggs article' => ['/blog/can-cats-eat-eggs'],
            'cheese article' => ['/blog/can-cats-eat-cheese'],
            'bread article' => ['/blog/can-cats-eat-bread'],
            'bananas article' => ['/blog/can-cats-eat-bananas'],
            'popcorn article' => ['/blog/can-cats-eat-popcorn'],
            'chickpeas article' => ['/blog/can-cats-eat-chickpeas'],
            'new owner article' => ['/blog/new-cat-owner-guide'],
            'age calculator' => ['/tools/cat-age-calculator'],
            'pregnancy calculator' => ['/tools/cat-pregnancy-calculator'],
            'calorie calculator' => ['/tools/cat-calorie-calculator'],
            'vaccination tracker' => ['/tools/cat-vaccination-tracker'],
            'weight checker' => ['/tools/cat-weight-checker'],
            'tools index' => ['/tools'],
            'food guides index' => ['/food-guides'],
            'fruits food guide' => ['/food-guides/fruits'],
            'vegetables food guide' => ['/food-guides/vegetables'],
            'toxic foods food guide' => ['/food-guides/toxic-foods'],
            'herbs and spices food guide' => ['/food-guides/herbs-and-spices'],
            'search' => ['/search?q=cat'],
        ];
    }

    /**
     * The em dash is the clearest tell of machine-written prose, and a reader
     * who senses filler leaves. Sentences here get a period or a comma.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_no_em_dashes_reach_the_reader(string $path): void
    {
        $html = $this->get($path)->assertOk()->getContent();

        $this->assertStringNotContainsString(
            "\u{2014}", $html, "$path renders an em dash"
        );
    }

    /**
     * British spellings cost the exact-match keyword as well as the tone:
     * Americans search "cat behavior problems", never "behaviour".
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_spelling_is_american(string $path): void
    {
        $html = $this->get($path)->assertOk()->getContent();

        // Checked against visible text, not raw HTML: a plain substring match
        // over the markup flags aria-labelledby for containing "labelled",
        // which is an attribute name, not a word choice. Script and style
        // blocks are stripped for the same reason a class name could match.
        $text = strip_tags(preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $html));

        foreach ([
            'behaviour', 'colour', 'favour', 'honour', 'recognise', 'recognisably',
            'labelled', 'whilst', 'amongst', 'centre', 'labour', 'organisation',
            'analyse', 'licence', 'defence', 'programme',
        ] as $british) {
            $this->assertDoesNotMatchRegularExpression(
                '/\b'.$british.'\w*/i', $text, "$path uses the British \"$british\""
            );
        }
    }

    /**
     * The locale pair and the lang attribute are how search and social are
     * told which market a page is written for. en_GB pointed at the wrong one.
     */
    public function test_the_page_declares_itself_american(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('<html lang="en-US"', $html);
        $this->assertStringContainsString('property="og:locale" content="en_US"', $html);
    }

    /** US order: "August 18, 2026", not "18 August 2026". */
    public function test_legal_dates_read_in_us_order(): void
    {
        foreach (['/terms', '/privacy'] as $path) {
            $this->get($path)->assertSee(
                \Illuminate\Support\Carbon::parse(
                    config($path === '/terms' ? 'legal.terms_effective' : 'legal.privacy_effective')
                )->format('F j, Y')
            );
        }
    }
}
