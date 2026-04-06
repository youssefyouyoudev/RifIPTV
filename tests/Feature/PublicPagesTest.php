<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_it_renders_the_legal_hub_and_policy_pages(): void
    {
        $this->get('/legal')
            ->assertRedirect('/trust-center');

        $this->get('/trust-center')
            ->assertSuccessful()
            ->assertSee('Trust center', false);

        $this->get('/trust-center/privacy-policy')
            ->assertSuccessful()
            ->assertSee('Privacy Policy', false);

        $this->get('/trust-center/terms-of-service')
            ->assertSuccessful()
            ->assertSee('Terms of Service', false);

        $this->get('/trust-center/security-safety')
            ->assertSuccessful()
            ->assertSee('Security & Safety', false);
    }

    public function test_it_renders_the_public_seo_pages(): void
    {
        $this->get('/')
            ->assertSuccessful()
            ->assertSee('Smart TV, Device Setup &amp; Technical Support in Morocco', false);

        $this->get('/services')
            ->assertSuccessful()
            ->assertSee('Device setup and technical support services in Morocco.', false);

        $this->get('/about')
            ->assertSuccessful()
            ->assertSee('Rifi Media is a Morocco-based technical support and device setup company.', false);

        $this->get('/contact')
            ->assertSuccessful()
            ->assertSee('Talk to the team about setup, troubleshooting, or billing follow-up.', false);

        $this->get('/packages')
            ->assertSuccessful()
            ->assertSee('Choose the support plan that fits your setup and follow-up needs.', false);

        $this->get('/faq')
            ->assertSuccessful()
            ->assertSee('Answers to common setup, onboarding, and technical support questions.', false);

        $this->get('/services/smart-tv-setup-morocco')
            ->assertSuccessful()
            ->assertSee('Smart TV setup in Morocco', false);

        $this->get('/smart-tv-setup-morocco')
            ->assertRedirect('/services/smart-tv-setup-morocco');
    }

    public function test_it_renders_seo_helper_files(): void
    {
        $this->get('/sitemap.xml')
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/services?lang=en', false)
            ->assertSee('/packages?lang=en', false)
            ->assertSee('/about?lang=en', false)
            ->assertSee('/contact?lang=en', false);

        $this->get('/robots.txt')
            ->assertSuccessful()
            ->assertSee('User-agent: *', false)
            ->assertSee('Host:', false)
            ->assertSee('Sitemap:', false);

        $this->get('/llms.txt')
            ->assertSuccessful()
            ->assertSee('Rifi Media', false)
            ->assertSee('device setup', false);
    }

    public function test_it_supports_locale_query_parameters_on_public_pages(): void
    {
        $this->get('/legal?lang=fr')
            ->assertRedirect('/trust-center?lang=fr');

        $this->get('/trust-center?lang=fr')
            ->assertSuccessful()
            ->assertSee('Centre de confiance', false);

        $this->get('/login?lang=es')
            ->assertSuccessful()
            ->assertSee('Inicia sesi', false)
            ->assertSee('noindex,nofollow', false);
    }
}
