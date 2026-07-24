<?php

namespace Tests\Feature;

use Tests\TestCase;

class GoogleSamlLoginTest extends TestCase
{
    public function test_google_sso_login_route_redirects_to_google_saml_entrypoint(): void
    {
        $response = $this->get('/login/google');

        $response->assertRedirectContains('/saml2/google/login');
    }
}
