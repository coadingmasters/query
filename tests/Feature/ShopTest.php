<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShopTest extends TestCase
{
    public function test_the_shop_page_renders(): void
    {
        $this->get('/shop')
            ->assertOk()
            ->assertSee('Shop by Category')
            ->assertSee('Popular Picks for Cat Parents');
    }

    public function test_every_category_is_listed(): void
    {
        $response = $this->get('/shop');

        foreach (config('shop.categories') as $category) {
            $response->assertSee($category['title']);
        }
    }

    /**
     * No product on the page has a real price, rating or purchase link yet -
     * none of that exists until real products and an affiliate account are
     * chosen, and showing invented numbers would be exactly the fake social
     * proof this site avoids everywhere else.
     */
    public function test_picks_do_not_show_fabricated_prices_or_ratings(): void
    {
        $html = $this->get('/shop')->getContent();

        $this->assertStringNotContainsString('$', $html);
        $this->assertMatchesRegularExpression('/Coming soon/', $html);
        $this->assertDoesNotMatchRegularExpression('/\d\.\d\s*(stars?|★)/i', $html);
    }

    public function test_the_shop_link_is_in_the_main_nav(): void
    {
        $this->get('/')->assertSee('href="'.route('shop.index').'"', false);
    }
}
