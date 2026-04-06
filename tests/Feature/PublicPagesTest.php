<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_it_renders_the_legal_hub_and_policy_pages(): void
    {
        $this->get('/legal')
            ->assertSuccessful()
            ->assertSee('Trust center', false);

        $this->get('/legal/privacy-policy')
            ->assertSuccessful()
            ->assertSee('Privacy Policy', false);

        $this->get('/legal/terms-of-service')
            ->assertSuccessful()
            ->assertSee('Terms of Service', false);

        $this->get('/legal/security-safety')
            ->assertSuccessful()
            ->assertSee('Security & Safety', false);
    }

    public function test_it_renders_the_public_seo_pages(): void
    {
        $this->get('/services')
            ->assertSuccessful()
            ->assertSee('Device Setup Services', false);

        $this->get('/about')
            ->assertSuccessful()
            ->assertSee('About RIF Media', false);

        $this->get('/contact')
            ->assertSuccessful()
            ->assertSee('Contact RIF Media', false);
    }

    public function test_it_renders_seo_helper_files(): void
    {
        $this->get('/sitemap.xml')
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/services?lang=en', false)
            ->assertSee('/about?lang=en', false)
            ->assertSee('/contact?lang=en', false);

        $this->get('/robots.txt')
            ->assertSuccessful()
            ->assertSee('User-agent: *', false)
            ->assertSee('Host:', false)
            ->assertSee('Sitemap:', false);

        $this->get('/llms.txt')
            ->assertSuccessful()
            ->assertSee('RIF Media', false)
            ->assertSee('device setup', false);
    }

    public function test_it_supports_locale_query_parameters_on_public_pages(): void
    {
        $this->get('/legal?lang=fr')
            ->assertSuccessful()
            ->assertSee('Centre de confiance', false);

        $this->get('/login?lang=es')
            ->assertSuccessful()
            ->assertSee('Inicia sesi', false)
            ->assertSee('noindex,nofollow', false);
    }
}
