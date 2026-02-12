<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Give every test request a valid CSRF token
        $token = 'test-csrf-token';

        $this->withSession(['_token' => $token]);
        $this->withHeader('X-CSRF-TOKEN', $token);
        $this->withHeader('X-XSRF-TOKEN', $token);
    }
}