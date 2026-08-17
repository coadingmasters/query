<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_address_can_subscribe(): void
    {
        $this->post('/subscribe', ['email' => 'reader@example.com'])
            ->assertRedirect()
            ->assertSessionHas('subscribed');

        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.com']);
    }

    public function test_the_address_is_stored_lowercase(): void
    {
        $this->post('/subscribe', ['email' => 'Reader@Example.COM']);

        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.com']);
    }

    /** Subscribing twice should not create a second row or fail on the unique index. */
    public function test_subscribing_twice_is_harmless(): void
    {
        $this->post('/subscribe', ['email' => 'reader@example.com']);
        $this->post('/subscribe', ['email' => 'reader@example.com'])->assertSessionHas('subscribed');

        $this->assertSame(1, Subscriber::where('email', 'reader@example.com')->count());
    }

    public function test_resubscribing_clears_an_earlier_unsubscribe(): void
    {
        Subscriber::create(['email' => 'reader@example.com'])
            ->forceFill(['unsubscribed_at' => now()])->save();

        $this->post('/subscribe', ['email' => 'reader@example.com']);

        $this->assertNull(Subscriber::first()->unsubscribed_at);
    }

    public function test_an_invalid_address_is_rejected(): void
    {
        $this->post('/subscribe', ['email' => 'not-an-address'])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('subscribers', 0);
    }

    /**
     * The honeypot is invisible to people, so a filled one means a bot. It is
     * answered like a success so the bot has nothing to learn, but nothing is
     * written to the list.
     */
    public function test_the_honeypot_silently_drops_bots(): void
    {
        $this->post('/subscribe', [
            'email' => 'bot@example.com',
            'website' => 'http://spam.example',
        ])->assertSessionHas('subscribed');

        $this->assertDatabaseCount('subscribers', 0);
    }
}
