<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_public_pages_send_core_security_headers(): void
    {
        $response = $this->get('/');

        $response
            ->assertSuccessful()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', config('security.referrer_policy'))
            ->assertHeader('Permissions-Policy', config('security.permissions_policy'));
    }

    public function test_secure_requests_include_hsts_header(): void
    {
        $response = $this->get('https://localhost/');

        $response
            ->assertSuccessful()
            ->assertHeader('Strict-Transport-Security');
    }
}
