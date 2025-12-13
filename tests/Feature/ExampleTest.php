<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test unauthenticated user is redirected to login.
     */
    public function test_unauthenticated_user_is_redirected(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    /**
     * Test public verification page is accessible.
     */
    public function test_verification_page_is_accessible(): void
    {
        $response = $this->get('/verify');

        $response->assertStatus(200);
    }
}
