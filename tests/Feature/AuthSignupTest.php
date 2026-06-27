<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthSignupTest extends TestCase
{
    public function test_signup_page_displays_a_registration_form(): void
    {
        $response = $this->get('/signup');

        $response->assertOk();
        $response->assertSee('Sign Up');
        $response->assertSee('Fullname');
        $response->assertSee('Create password');
    }
}
