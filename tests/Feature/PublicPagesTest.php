<?php

it('renders the legal hub and policy pages', function () {
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
});

it('renders the new public seo pages', function () {
    $this->get('/services')
        ->assertSuccessful()
        ->assertSee('Device Setup Services', false);

    $this->get('/about')
        ->assertSuccessful()
        ->assertSee('About RIF Media', false);

    $this->get('/contact')
        ->assertSuccessful()
        ->assertSee('Contact and Support', false);
});

it('renders seo helper files', function () {
    $this->get('/sitemap.xml')
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee('/services?lang=en', false)
        ->assertSee('/about?lang=en', false)
        ->assertSee('/contact?lang=en', false);

    $this->get('/robots.txt')
        ->assertSuccessful()
        ->assertSee('User-agent: *', false)
        ->assertSee('Sitemap:', false);
});

it('supports locale query parameters on public pages', function () {
    $this->get('/legal?lang=fr')
        ->assertSuccessful()
        ->assertSee('Centre de confiance', false);

    $this->get('/login?lang=es')
        ->assertSuccessful()
        ->assertSee('Inicia sesión', false)
        ->assertSee('noindex,nofollow', false);
});
