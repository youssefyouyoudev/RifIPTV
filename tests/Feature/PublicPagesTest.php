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
            ->assertSee('Digital entertainment solutions', false)
            ->assertSee('for Morocco', false);

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
            ->assertSee('Choose the Smart TV pack that fits your setup and follow-up needs.', false)
            ->assertSee('80', false)
            ->assertSee('140', false)
            ->assertSee('200', false)
            ->assertSee('Best Value', false);

        $this->get('/faq')
            ->assertSuccessful()
            ->assertSee('Answers to common setup, onboarding, and technical support questions.', false);

        $this->get('/help-center')
            ->assertSuccessful()
            ->assertSee('Help center', false);

        $this->get('/streaming-services-maroc')
            ->assertSuccessful()
            ->assertSee('Streaming services in Morocco', false);

        $this->get('/digital-entertainment-maroc')
            ->assertSuccessful()
            ->assertSee('Digital entertainment in Morocco', false);

        $this->get('/smart-tv-setup-maroc')
            ->assertSuccessful()
            ->assertSee('Smart TV setup in Morocco', false);

        $this->get('/media-solutions-maroc')
            ->assertSuccessful()
            ->assertSee('Media solutions in Morocco', false);

        $this->get('/streaming-support-nador')
            ->assertSuccessful()
            ->assertSee('Streaming support in Nador', false);

        $this->get('/smart-tv-setup-nador')
            ->assertSuccessful()
            ->assertSee('Smart TV setup in Nador', false);

        $this->get('/device-configuration-morocco')
            ->assertSuccessful()
            ->assertSee('Device configuration in Morocco', false);

        $this->get('/technical-support-morocco')
            ->assertSuccessful()
            ->assertSee('Technical support in Morocco', false);

        $this->get('/blog')
            ->assertSuccessful()
            ->assertSee('Useful setup and support guides for Morocco', false);

        $this->get('/blog/best-streaming-setup-morocco')
            ->assertSuccessful()
            ->assertSee('Best streaming setup in Morocco', false);

        $this->get('/services/smart-tv-setup-morocco')
            ->assertRedirect('/smart-tv-setup-morocco');
    }

    public function test_it_renders_seo_helper_files(): void
    {
        $this->get('/sitemap.xml')
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('pages.services'), false)
            ->assertSee(route('pages.packages'), false)
            ->assertSee(route('pages.about'), false)
            ->assertSee(route('pages.contact'), false)
            ->assertSee(route('pages.service', 'app-installation-help'), false)
            ->assertSee(route('pages.service', 'streaming-services-maroc'), false)
            ->assertSee(route('seo.blog'), false);

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

    public function test_arabic_public_pages_render_without_visible_mojibake_markers(): void
    {
        $this->get('/?lang=ar')
            ->assertSuccessful()
            ->assertSee('dir="rtl"', false)
            ->assertSee('حلول الترفيه الرقمي', false)
            ->assertDontSee('Basic Support', false)
            ->assertDontSee('Ø', false)
            ->assertDontSee('Ã', false);

        $this->get('/help-center?lang=ar')
            ->assertSuccessful()
            ->assertSee('مركز المساعدة', false)
            ->assertDontSee('Ø', false)
            ->assertDontSee('Ã', false);

        $this->get('/packages?lang=ar')
            ->assertSuccessful()
            ->assertSee('خطط الدعم', false)
            ->assertDontSee('Basic Support', false)
            ->assertDontSee('Ø', false)
            ->assertDontSee('Ã', false);

        $this->get('/smart-tv-setup-maroc?lang=ar')
            ->assertSuccessful()
            ->assertSee('إعداد Smart TV في المغرب', false)
            ->assertDontSee('Ø', false)
            ->assertDontSee('Ã', false);
    }
}
