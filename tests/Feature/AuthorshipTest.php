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

        $this->assertStringNotContainsString('id="founder"', $html);
        $this->assertStringNotContainsString('"@type":"Person"', $html);
        $this->assertStringNotContainsString('"founder"', $html);
    }

    public function test_a_configured_author_appears_on_the_page_and_in_the_graph(): void
    {
        $this->withAuthor();

        $html = $this->get('/about')->assertOk()->getContent();

        $this->assertStringContainsString('id="founder"', $html);
        $this->assertStringContainsString('A Real Person', $html);
        $this->assertStringContainsString('"@type":"Person"', $html);

        // The Organization points at the Person, and the page names them as
        // its author. Both are what turn a bio into an authorship signal.
        $this->assertStringContainsString('"founder":{"@id"', $html);
        $this->assertStringContainsString('"author":{"@id"', $html);
    }

    /**
     * sameAs is the part a reader or a rater can actually follow. A name with
     * nothing behind it is an assertion; a name with a profile is a check.
     */
    public function test_profiles_are_published_as_sameas_and_marked_rel_me(): void
    {
        $this->withAuthor();

        $html = $this->get('/about')->getContent();

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

        $this->assertStringNotContainsString('Reviewed by', $this->get('/about')->getContent());
    }

    public function test_a_named_reviewer_is_credited_when_one_exists(): void
    {
        $this->withAuthor([
            'author.reviewer.name' => 'Dr Example',
            'author.reviewer.credentials' => 'DVM',
            'author.reviewer.reviewed_on' => '2026-08-18',
        ]);

        $this->get('/about')
            ->assertSee('Reviewed by')
            ->assertSee('Dr Example')
            ->assertSee('DVM');
    }
}
