<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Cat health is YMYL, where Google's guidance leans hardest on who wrote a
 * thing and whether that person can be checked. These tests hold two lines:
 * nothing about an author appears until a real one is configured, and what
 * does appear never claims clinical credentials the site has not earned.
 */
class AuthorshipTest extends TestCase
{
    private function withAuthor(array $overrides = []): void
    {
        config([
            'author.founder.name' => 'A Real Person',
            'author.founder.profiles' => ['https://www.linkedin.com/in/example'],
            ...$overrides,
        ]);
    }

    public function test_nothing_is_attributed_while_no_author_is_configured(): void
    {
        config(['author.founder.name' => null]);

        $html = $this->get('/about')->assertOk()->getContent();

        $this->assertStringNotContainsString('Written by', $html);
        $this->assertStringNotContainsString('"@type":"Person"', $html);
        $this->assertStringNotContainsString('"founder"', $html);
    }

    /**
     * A profile page for nobody is worse than a 404: it is an authorship claim
     * with nothing behind it.
     */
    public function test_the_author_page_is_absent_while_no_author_is_configured(): void
    {
        config(['author.founder.name' => null]);

        $this->get('/author')->assertNotFound();
    }

    /**
     * Authorship gets its own URL rather than an anchor inside /about. It is a
     * claim about a person, and a page of its own is what search engines and
     * quality raters expect to find behind a byline.
     */
    public function test_the_author_page_carries_the_profile(): void
    {
        $this->withAuthor();

        $html = $this->get('/author')->assertOk()->getContent();

        $this->assertStringContainsString('A Real Person', $html);
        $this->assertStringContainsString('"@type":"ProfilePage"', $html);
        $this->assertStringContainsString('"mainEntity"', $html);
    }

    /** The about page links out to it rather than repeating it. */
    public function test_the_about_page_points_at_the_author_page(): void
    {
        $this->withAuthor();

        $this->get('/about')
            ->assertSee('href="'.route('author').'"', false)
            ->assertSee('A Real Person');
    }

    public function test_the_sitemap_lists_the_author_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/author', false);
    }

    public function test_a_configured_author_appears_on_the_page_and_in_the_graph(): void
    {
        $this->withAuthor();

        $html = $this->get('/author')->assertOk()->getContent();

        $this->assertStringContainsString('A Real Person', $html);
        $this->assertStringContainsString('"@type":"Person"', $html);

        // The Organization points at the Person, and the profile page names
        // them as its subject. A ProfilePage takes mainEntity for that, not
        // author: the page is about the person rather than written by them.
        $this->assertStringContainsString('"founder":{"@id"', $html);
        $this->assertStringContainsString('"mainEntity":{"@id"', $html);

        // author is the right relationship on a page they wrote.
        $this->assertStringContainsString('"author":{"@id"', $this->get('/about')->getContent());
    }

    /**
     * sameAs is the part a reader or a rater can actually follow. A name with
     * nothing behind it is an assertion; a name with a profile is a check.
     */
    public function test_profiles_are_published_as_sameas_and_marked_rel_me(): void
    {
        $this->withAuthor();

        $html = $this->get('/author')->getContent();

        $this->assertStringContainsString('"sameAs"', $html);
        $this->assertStringContainsString('rel="me noopener"', $html);
    }

    /**
     * The founder is a developer, not a clinician. If that ever changes it has
     * to be because someone qualified is named in the reviewer block, not
     * because the bio quietly started implying it.
     */
    public function test_the_bio_claims_no_veterinary_credentials(): void
    {
        $bio = mb_strtolower(implode(' ', config('author.founder.bio')).' '.config('author.founder.role'));

        foreach (['dvm', 'vmd', 'bvsc', 'mrcvs', 'veterinary nurse', 'veterinary surgeon'] as $credential) {
            $this->assertStringNotContainsString(
                $credential, $bio,
                "the founder bio claims \"$credential\"; that belongs in the reviewer block, and only if true"
            );
        }

        $this->assertStringContainsString(
            'not a veterinarian', mb_strtolower(implode(' ', config('author.founder.bio'))),
            'the bio should say plainly that the author is not a vet'
        );
    }

    /** No reviewer configured, no reviewed-by line anywhere. */
    public function test_no_review_is_claimed_without_a_named_reviewer(): void
    {
        $this->withAuthor(['author.reviewer.name' => null]);

        $this->assertStringNotContainsString('Reviewed by', $this->get('/author')->getContent());
    }

    public function test_a_named_reviewer_is_credited_when_one_exists(): void
    {
        $this->withAuthor([
            'author.reviewer.name' => 'Dr Example',
            'author.reviewer.credentials' => 'DVM',
            'author.reviewer.reviewed_on' => '2026-08-18',
        ]);

        $this->get('/author')
            ->assertSee('Reviewed by')
            ->assertSee('Dr Example')
            ->assertSee('DVM');
    }
}
