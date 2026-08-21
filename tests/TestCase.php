<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Blog content lives in the database now, not a config file. Seeding
     * once, before the first test's transaction opens, means every test
     * class can assert against the real migrated posts and categories
     * without re-seeding itself or depending on what ran before it.
     */
    protected bool $seed = true;
}
