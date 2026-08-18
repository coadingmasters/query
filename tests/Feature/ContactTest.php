<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private array $valid = [
        'name' => 'Alex Morgan',
        'email' => 'alex@example.com',
        'subject' => 'A correction to a guide',
        'message' => 'The broccoli guide says cooked only, but the source you cite says raw is fine too.',
    ];

    public function test_the_contact_page_renders(): void
    {
        $this->get('/contact')
            ->assertOk()
            // The h1 itself, not the header's "Get in touch" link — that one
            // is on every page and would pass whatever this page rendered.
            ->assertSee('hear</span> from you!', false)
            ->assertSee(config('brand.email'));
    }

    public function test_it_is_reachable_from_the_header_and_footer(): void
    {
        $this->get('/')
            ->assertSee('href="'.route('contact').'"', false)
            ->assertSee('Contact Us');
    }

    /** The nav wording changed, but the URLs must not — they are indexed. */
    public function test_the_slugs_stay_short(): void
    {
        $this->assertSame('/about', parse_url(route('about'), PHP_URL_PATH));
        $this->assertSame('/contact', parse_url(route('contact'), PHP_URL_PATH));
    }

    public function test_a_message_can_be_sent(): void
    {
        $this->post('/contact', $this->valid)
            ->assertRedirect()
            ->assertSessionHas('sent');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alex@example.com',
            'subject' => 'A correction to a guide',
        ]);
    }

    public function test_every_field_is_required(): void
    {
        $this->post('/contact', [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_a_bad_address_is_rejected(): void
    {
        $this->post('/contact', [...$this->valid, 'email' => 'not-an-address'])
            ->assertSessionHasErrors('email');
    }

    /** One-word messages are almost always accidental submissions or spam. */
    public function test_a_very_short_message_is_rejected(): void
    {
        $this->post('/contact', [...$this->valid, 'message' => 'hi'])
            ->assertSessionHasErrors('message');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    /**
     * The subject is a fixed list. Accepting anything typed in would let the
     * field carry arbitrary text into the inbox and into any future email.
     */
    public function test_an_unlisted_subject_is_rejected(): void
    {
        $this->post('/contact', [...$this->valid, 'subject' => 'Buy cheap watches'])
            ->assertSessionHasErrors('subject');
    }

    public function test_the_honeypot_silently_drops_bots(): void
    {
        $this->post('/contact', [...$this->valid, 'website' => 'http://spam.example'])
            ->assertSessionHas('sent');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_what_the_user_typed_survives_a_validation_error(): void
    {
        $this->post('/contact', [...$this->valid, 'email' => 'bad'])
            ->assertSessionHasInput('name', 'Alex Morgan')
            ->assertSessionHasInput('message', $this->valid['message']);
    }

    public function test_a_message_is_not_marked_handled_on_arrival(): void
    {
        $this->post('/contact', $this->valid);

        $this->assertNull(ContactMessage::first()->handled_at);
    }

    public function test_it_carries_contact_structured_data(): void
    {
        $this->get('/contact')
            ->assertSee('"@type":"ContactPage"', false)
            ->assertSee('"@type":"ContactPoint"', false);
    }

    /**
     * No invented phone number or postal address. The site has neither, and a
     * contact detail that does not work is worse than one that is absent.
     */
    public function test_it_publishes_no_contact_details_that_do_not_exist(): void
    {
        $html = $this->get('/contact')->getContent();

        $this->assertStringNotContainsString('"telephone"', $html);
        $this->assertStringNotContainsString('"address"', $html);
        $this->assertStringNotContainsString('tel:', $html);
    }

    /**
     * The artwork is the page. If a source file is renamed or dropped out of
     * config/images.php the component renders nothing at all, silently — so
     * the count is asserted rather than trusted.
     */
    public function test_every_illustration_on_the_page_resolves(): void
    {
        $html = $this->get('/contact')->getContent();

        foreach ([
            'purrquery-orange-tabby-cat-hero',
            'purrquery-cat-saying-hi',
            'purrquery-cat-waving-paw',
            'purrquery-cat-yarn-ball',
            'purrquery-cat-food-bowl-heart',
        ] as $name) {
            $this->assertStringContainsString("/images/$name-", $html, "$name did not render");
        }
    }

    /**
     * The form used to be swapped out for a success panel, which meant a
     * second message cost a page load to get back to. It now stays put and
     * simply comes back empty.
     */
    public function test_the_form_survives_a_successful_send(): void
    {
        $html = $this->post('/contact', [
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'subject' => 'A question about cat care',
            'message' => 'This is a long enough message to pass validation.',
        ])->assertRedirect()->getContent();

        $html = $this->followingRedirects()->get('/contact')->getContent();

        $this->assertStringContainsString('name="message"', $html, 'the form was replaced');
    }

    public function test_a_successful_send_announces_itself(): void
    {
        $html = $this->withSession(['sent' => true])->get('/contact')->getContent();

        $this->assertStringContainsString('data-result-dialog', $html);
        $this->assertStringContainsString('Message sent!', $html);
        $this->assertStringContainsString('name="message"', $html, 'the form was replaced');
    }

    /**
     * The dialog carries the open attribute in the markup, so someone without
     * JavaScript reads the result as an ordinary panel instead of nothing.
     */
    public function test_the_result_is_readable_without_javascript(): void
    {
        $html = $this->withSession(['sent' => true])->get('/contact')->getContent();

        $this->assertMatchesRegularExpression('/<dialog[^>]*\sopen\b/', $html);
    }

    public function test_validation_errors_are_announced_and_keep_what_was_typed(): void
    {
        $html = $this->from('/contact')->post('/contact', [
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'subject' => 'A question about cat care',
            'message' => 'too short',
        ])->assertRedirect('/contact');

        $html = $this->followingRedirects()->from('/contact')->post('/contact', [
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'subject' => 'A question about cat care',
            'message' => 'too short',
        ])->getContent();

        $this->assertStringContainsString('data-result-dialog', $html);
        $this->assertStringContainsString('Almost there', $html);
        $this->assertStringContainsString('value="Alex Morgan"', $html);
    }

    public function test_the_sitemap_lists_the_contact_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/contact', false);
    }
}
