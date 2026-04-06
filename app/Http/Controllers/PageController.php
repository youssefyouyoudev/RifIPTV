<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function services(): View
    {
        return view('pages.show', $this->pagePayload('services'));
    }

    public function about(): View
    {
        return view('pages.show', $this->pagePayload('about'));
    }

    public function contact(): View
    {
        return view('pages.show', $this->pagePayload('contact'));
    }

    public function faq(): View
    {
        return view('pages.faq', [
            'page' => $this->faqPage(),
            'faqItems' => $this->faqItems(),
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => 'FAQ', 'url' => route('pages.faq')],
            ],
        ]);
    }

    public function service(string $slug): View
    {
        $page = $this->servicePages()[$slug] ?? abort(404);

        return view('pages.show', [
            'page' => $page,
            'pageKey' => $slug,
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => __('site.nav.features'), 'url' => route('pages.services')],
                ['label' => $page['headline'], 'url' => route('pages.service', $slug)],
            ],
        ]);
    }

    public function legacyLegal(): RedirectResponse
    {
        return redirect()->route('pages.trust', request()->query(), 301);
    }

    protected function pagePayload(string $key): array
    {
        $page = match ($key) {
            'services' => $this->servicesPage(),
            'about' => $this->aboutPage(),
            default => $this->contactPage(),
        };

        return [
            'page' => $page,
            'pageKey' => $key,
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => $page['headline'], 'url' => route("pages.{$key}")],
            ],
        ];
    }

    protected function servicesPage(): array
    {
        return match (app()->getLocale()) {
            'fr' => [
                'meta_title' => 'Services de configuration d appareils au Maroc | Rifi Media',
                'meta_description' => 'Configuration Smart TV, aide a l installation d applications, depannage des appareils connectes et support technique au Maroc.',
                'kicker' => 'Services',
                'headline' => 'Services de configuration d appareils et support technique au Maroc.',
                'title' => 'Configuration Smart TV, aide applicative et assistance technique',
                'description' => 'Nous aidons les clients dans tout le Maroc a configurer leurs appareils, organiser leurs applications utiles et avancer avec un support humain clair.',
                'cards' => [
                    ['title' => 'Configuration Smart TV', 'text' => 'Installation initiale, reglages pratiques et verification du bon fonctionnement.'],
                    ['title' => 'Guidage applicatif', 'text' => 'Aide a l installation, a l organisation et a la connexion de vos applications utiles.'],
                    ['title' => 'Depannage d appareils', 'text' => 'Resolution des blocages courants sur Smart TV, box Android, mobile ou tablette.'],
                    ['title' => 'Onboarding de compte', 'text' => 'Mise en route plus claire pour les comptes, paiements et etapes de support.'],
                ],
                'links' => $this->serviceLinks(),
            ],
            'es' => [
                'meta_title' => 'Servicios de configuracion de dispositivos en Marruecos | Rifi Media',
                'meta_description' => 'Configuracion de Smart TV, ayuda de instalacion de aplicaciones, solucion de problemas y soporte tecnico en Marruecos.',
                'kicker' => 'Servicios',
                'headline' => 'Servicios de configuracion de dispositivos y soporte tecnico en Marruecos.',
                'title' => 'Configuracion de Smart TV, guia de aplicaciones y asistencia tecnica',
                'description' => 'Ayudamos a clientes en todo Marruecos a configurar sus dispositivos, ordenar aplicaciones utiles y mantener un soporte tecnico claro.',
                'cards' => [
                    ['title' => 'Configuracion de Smart TV', 'text' => 'Instalacion inicial, ajustes practicos y revision del funcionamiento.'],
                    ['title' => 'Guia de aplicaciones', 'text' => 'Ayuda para instalar, organizar e iniciar aplicaciones utiles.'],
                    ['title' => 'Solucion de problemas', 'text' => 'Asistencia para fallos frecuentes en Smart TV, Android box, movil o tableta.'],
                    ['title' => 'Ayuda de onboarding', 'text' => 'Un comienzo mas claro para cuentas, pagos y pasos de soporte.'],
                ],
                'links' => $this->serviceLinks(),
            ],
            'ar' => [
                'meta_title' => 'خدمات إعداد الأجهزة والدعم التقني في المغرب | Rifi Media',
                'meta_description' => 'إعداد Smart TV، وضبط التطبيقات، وحل مشاكل الأجهزة المتصلة، والمساعدة التقنية في مختلف أنحاء المغرب.',
                'kicker' => 'الخدمات',
                'headline' => 'خدمات إعداد الأجهزة والدعم التقني في المغرب.',
                'title' => 'إعداد Smart TV، وإرشاد التطبيقات، والمساعدة التقنية',
                'description' => 'نساعد العملاء في جميع أنحاء المغرب على إعداد أجهزتهم، وتنظيم التطبيقات المفيدة، والحصول على متابعة تقنية واضحة وآمنة.',
                'cards' => [
                    ['title' => 'إعداد Smart TV', 'text' => 'تهيئة أولية، وضبط عملي، والتأكد من أن الجهاز يعمل بشكل مستقر.'],
                    ['title' => 'إرشاد التطبيقات', 'text' => 'مساعدة في تثبيت التطبيقات المفيدة وتنظيمها وتسجيل الدخول إليها.'],
                    ['title' => 'حل مشاكل الأجهزة', 'text' => 'دعم لحل الأعطال الشائعة على Smart TV وAndroid box والهاتف واللوحي.'],
                    ['title' => 'المساعدة في بدء الحساب', 'text' => 'بداية أوضح في الحساب والدفع ومسار الدعم التقني.'],
                ],
                'links' => $this->primaryLinks(),
            ],
            default => [
                'meta_title' => 'Device Setup Services in Morocco | Rifi Media',
                'meta_description' => 'Smart TV setup, app installation guidance, connected device troubleshooting, account onboarding help, and technical support across Morocco.',
                'kicker' => 'Services',
                'headline' => 'Smart TV, device setup, and technical support services in Morocco.',
                'title' => 'Clear setup services for connected devices and everyday technical support',
                'description' => 'We help clients across Morocco set up devices, organize useful apps, resolve configuration issues, and move through onboarding with human support.',
                'cards' => [
                    ['title' => 'Smart TV setup', 'text' => 'First-time configuration, practical settings, and a smoother setup experience.'],
                    ['title' => 'App installation guidance', 'text' => 'Hands-on help for safe app setup, sign-in steps, and everyday organization.'],
                    ['title' => 'Device troubleshooting', 'text' => 'Help with common issues on Smart TV, Android boxes, phones, tablets, and related devices.'],
                    ['title' => 'Account onboarding help', 'text' => 'A clearer first journey for account setup, payment review, and support follow-up.'],
                ],
                'links' => $this->serviceLinks(),
            ],
        };
    }

    protected function aboutPage(): array
    {
        return match (app()->getLocale()) {
            'ar' => [
                'meta_title' => 'من نحن | Rifi Media للدعم التقني في المغرب',
                'meta_description' => 'تعرّف على طريقة عمل Rifi Media في إعداد الأجهزة وبدء الحساب والمتابعة التقنية في مختلف أنحاء المغرب.',
                'kicker' => 'من نحن',
                'headline' => 'Rifi Media علامة تقنية تساعد العملاء في المغرب بوضوح وثقة.',
                'title' => 'خدمة إعداد ومساندة مبنية على الوضوح والمتابعة البشرية',
                'description' => 'نركز على إعداد الأجهزة، وشرح الخطوات العملية، وتقديم متابعة تقنية واضحة وسهلة الفهم قبل الطلب وبعده.',
                'cards' => [
                    ['title' => 'عملية واضحة', 'text' => 'كل مرحلة مفهومة: طلب، مراجعة، إرشاد، ثم متابعة.'],
                    ['title' => 'دعم بشري', 'text' => 'يعرف العميل من يتابع معه وما هي الخطوة التالية.'],
                    ['title' => 'خدمة على مستوى المغرب', 'text' => 'الخدمة موجهة للعملاء في مختلف أنحاء المغرب.'],
                ],
                'links' => $this->primaryLinks(),
            ],
            default => [
                'meta_title' => 'About Rifi Media | Device Setup & Technical Support in Morocco',
                'meta_description' => 'Learn how Rifi Media approaches Smart TV setup, app guidance, onboarding, and technical support for clients across Morocco.',
                'kicker' => __('site.nav.about'),
                'headline' => 'Rifi Media is a technical support brand built for clients across Morocco.',
                'title' => 'A clearer approach to device setup, app guidance, and follow-up',
                'description' => 'Rifi Media is built around calm communication, practical onboarding, and trustworthy support for Smart TV, connected devices, and app setup help.',
                'cards' => [
                    ['title' => 'Transparent workflow', 'text' => 'Each step stays visible: request, review, guidance, and follow-up support.'],
                    ['title' => 'Human support', 'text' => 'Clients know who is helping them and what happens next.'],
                    ['title' => 'Morocco-wide service area', 'text' => 'The business supports clients across Morocco, not one city alone.'],
                ],
                'links' => $this->primaryLinks(),
            ],
        };
    }

    protected function contactPage(): array
    {
        $email = config('seo.contact_email', 'contact@rifimedia.com');
        $whatsapp = config('seo.whatsapp_url', 'https://wa.me/212663323824');
        $supportHours = config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00');

        return match (app()->getLocale()) {
            'ar' => [
                'meta_title' => 'التواصل مع Rifi Media | دعم تقني في المغرب',
                'meta_description' => 'تواصل مع Rifi Media للمساعدة في إعداد الأجهزة وضبط التطبيقات والدفع والمتابعة التقنية في مختلف أنحاء المغرب.',
                'kicker' => 'التواصل',
                'headline' => 'تحدث مع الفريق للمساعدة التقنية أو الإعداد أو متابعة الدفع.',
                'title' => 'وسائل تواصل واضحة للعملاء داخل المغرب',
                'description' => 'نستقبل طلبات إعداد الأجهزة، والمساعدة في التطبيقات، وحل المشكلات، وبدء الحساب، والمتابعة التقنية في جميع أنحاء المغرب.',
                'cards' => [
                    ['title' => 'البريد الإلكتروني', 'text' => $email],
                    ['title' => 'واتساب', 'text' => $whatsapp],
                    ['title' => 'ساعات الدعم', 'text' => $supportHours],
                    ['title' => 'نطاق الخدمة', 'text' => 'المغرب'],
                ],
                'links' => $this->primaryLinks(),
            ],
            default => [
                'meta_title' => 'Contact Rifi Media | Technical Support in Morocco',
                'meta_description' => 'Contact Rifi Media for Smart TV setup help, app guidance, payment follow-up, and technical support anywhere in Morocco.',
                'kicker' => __('site.nav.support'),
                'headline' => 'Talk to the team for device setup, payment questions, or technical support.',
                'title' => 'Simple contact options for clients across Morocco',
                'description' => 'We answer questions about Smart TV setup, app guidance, onboarding, troubleshooting, and ongoing technical support across Morocco.',
                'cards' => [
                    ['title' => 'Email', 'text' => $email],
                    ['title' => 'WhatsApp', 'text' => $whatsapp],
                    ['title' => 'Support hours', 'text' => $supportHours],
                    ['title' => 'Service area', 'text' => 'Morocco'],
                ],
                'links' => $this->primaryLinks(),
            ],
        };
    }

    protected function faqPage(): array
    {
        return match (app()->getLocale()) {
            'ar' => [
                'meta_title' => 'الأسئلة الشائعة | Rifi Media',
                'meta_description' => 'إجابات واضحة عن إعداد Smart TV، وضبط التطبيقات، والدفع، والدعم التقني من Rifi Media في المغرب.',
                'kicker' => 'الأسئلة الشائعة',
                'headline' => 'إجابات سريعة عن الإعداد والدفع والدعم التقني.',
                'description' => 'تشرح هذه الصفحة طريقة عمل Rifi Media في إعداد الأجهزة، وبدء الحساب، والمتابعة، والدعم التقني داخل المغرب.',
            ],
            default => [
                'meta_title' => 'FAQ | Rifi Media',
                'meta_description' => 'Clear answers about Smart TV setup, app guidance, payment steps, onboarding help, and technical support from Rifi Media in Morocco.',
                'kicker' => 'FAQ',
                'headline' => 'Answers to common setup, onboarding, and technical support questions.',
                'description' => 'This FAQ explains how Rifi Media helps clients with Smart TV setup, app installation guidance, troubleshooting, and support in Morocco.',
            ],
        };
    }

    protected function faqItems(): array
    {
        return match (app()->getLocale()) {
            'ar' => [
                ['q' => 'ما الذي تقدمه Rifi Media بالضبط؟', 'a' => 'نساعد في إعداد Smart TV، وتثبيت التطبيقات، وحل مشاكل الأجهزة، والمتابعة التقنية.'],
                ['q' => 'هل توفرون محتوى إعلاميًا؟', 'a' => 'لا. نحن لا نوفر ولا نستضيف أي محتوى إعلامي، بل نقدم فقط خدمات الإعداد والدعم التقني.'],
                ['q' => 'هل تعملون في جميع أنحاء المغرب؟', 'a' => 'نعم. نطاق الخدمة يشمل المغرب مع متابعة عن بُعد ودعم بشري واضح.'],
                ['q' => 'ما هي خيارات الدفع المتاحة؟', 'a' => 'يمكن استخدام الدفع بالبطاقة عبر Paddle، كما تتم مراجعة التحويلات البنكية المحلية يدويًا.'],
            ],
            default => [
                ['q' => 'What does Rifi Media actually do?', 'a' => 'We help clients with Smart TV setup, app installation guidance, connected device troubleshooting, and practical technical support.'],
                ['q' => 'Do you provide media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
                ['q' => 'Do you work across Morocco?', 'a' => 'Yes. Our service area covers Morocco with remote guidance and human support.'],
                ['q' => 'What payment options are available?', 'a' => 'Card payments can be handled through Paddle, and local bank transfers are reviewed manually.'],
            ],
        };
    }

    protected function servicePages(): array
    {
        return match (app()->getLocale()) {
            'ar' => [
                'smart-tv-setup' => $this->servicePage(
                    'إعداد Smart TV في المغرب',
                    'إعداد Smart TV في المغرب | Rifi Media',
                    'مساعدة احترافية في إعداد Smart TV وضبط الإعدادات الأساسية وتجهيز الأجهزة المتصلة في المغرب.'
                ),
                'app-installation-guidance' => $this->servicePage(
                    'إرشاد تثبيت التطبيقات',
                    'إرشاد تثبيت التطبيقات في المغرب | Rifi Media',
                    'مساعدة في تثبيت التطبيقات المفيدة وتنظيمها وتسجيل الدخول إليها على Smart TV والهاتف واللوحي في المغرب.'
                ),
                'device-troubleshooting' => $this->servicePage(
                    'حل مشاكل الأجهزة المتصلة',
                    'حل مشاكل الأجهزة المتصلة في المغرب | Rifi Media',
                    'دعم لحل مشاكل الإعداد والاتصال والاستقرار على Smart TV والأجهزة المتصلة في المغرب.'
                ),
                'account-onboarding-help' => $this->servicePage(
                    'المساعدة في بدء الحساب',
                    'المساعدة في بدء الحساب في المغرب | Rifi Media',
                    'إرشاد واضح لخطوات الحساب والدفع والمراجعة والمتابعة التقنية في المغرب.'
                ),
                'technical-support-morocco' => $this->servicePage(
                    'الدعم التقني في المغرب',
                    'الدعم التقني في المغرب | Rifi Media',
                    'دعم تقني احترافي لإعداد Smart TV والتطبيقات والأجهزة المتصلة في مختلف أنحاء المغرب.'
                ),
            ],
            default => [
                'smart-tv-setup' => $this->servicePage('Smart TV setup in Morocco', 'Smart TV setup in Morocco | Rifi Media', 'Professional Smart TV setup help in Morocco, including first-time configuration, practical settings, and connected device guidance.'),
                'app-installation-guidance' => $this->servicePage('App installation guidance', 'App installation guidance in Morocco | Rifi Media', 'App installation guidance in Morocco for Smart TV, mobile devices, tablets, and connected-device environments.'),
                'device-troubleshooting' => $this->servicePage('Connected device troubleshooting', 'Connected device troubleshooting in Morocco | Rifi Media', 'Technical troubleshooting for Smart TV, Android boxes, mobile devices, and connected-device issues across Morocco.'),
                'account-onboarding-help' => $this->servicePage('Account onboarding help', 'Account onboarding help in Morocco | Rifi Media', 'Account onboarding help in Morocco for first-time clients who need clearer setup, payment, and support guidance.'),
                'technical-support-morocco' => $this->servicePage('Technical support in Morocco', 'Technical support in Morocco | Rifi Media', 'Premium technical support in Morocco for Smart TV setup, app guidance, device troubleshooting, and connected-device help.'),
            ],
        };
    }

    protected function servicePage(string $headline, string $metaTitle, string $metaDescription): array
    {
        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'kicker' => __('site.nav.features'),
            'headline' => $headline,
            'title' => $headline,
            'description' => $metaDescription,
            'cards' => [
                ['title' => __('site.nav.features'), 'text' => $metaDescription],
                [
                    'title' => __('site.nav.support'),
                    'text' => app()->isLocale('ar')
                        ? 'خطوات واضحة، وتوجيه عملي، ومتابعة بشرية مناسبة لنوع الخدمة المختارة.'
                        : 'Clear guidance, practical steps, and human follow-up tailored to the selected service.',
                ],
            ],
            'schema_type' => 'Service',
            'links' => $this->primaryLinks(),
        ];
    }

    protected function primaryLinks(): array
    {
        if (app()->isLocale('ar')) {
            return [
                ['title' => 'كل الخدمات', 'text' => 'تصفح خدمات الإعداد والإرشاد والدعم التقني.', 'url' => route('pages.services')],
                ['title' => 'إعداد Smart TV', 'text' => 'تعرّف على خدمة إعداد التلفاز الذكي والخطوات الأولى.', 'url' => route('pages.service', 'smart-tv-setup')],
                ['title' => 'إرشاد تثبيت التطبيقات', 'text' => 'راجع خدمة تثبيت التطبيقات وضبطها على أجهزتك.', 'url' => route('pages.service', 'app-installation-guidance')],
                ['title' => 'الدعم التقني في المغرب', 'text' => 'اعرف كيف يعمل الدعم التقني في مختلف أنحاء المغرب.', 'url' => route('pages.service', 'technical-support-morocco')],
                ['title' => 'الأسئلة الشائعة', 'text' => 'اقرأ الإجابات الواضحة عن الإعداد والدفع والمتابعة.', 'url' => route('pages.faq')],
                ['title' => trans('legal.hub.kicker'), 'text' => 'راجع سياسات الخصوصية والاسترجاع وشروط الخدمة.', 'url' => route('pages.trust')],
                ['title' => 'التواصل', 'text' => 'تواصل مع الفريق لمساعدتك في الإعداد والدعم.', 'url' => route('pages.contact')],
            ];
        }

        return [
            ['title' => __('site.nav.features'), 'text' => 'Browse all setup and guidance services.', 'url' => route('pages.services')],
            ['title' => 'Smart TV setup', 'text' => 'Learn about Smart TV configuration and first-time setup help.', 'url' => route('pages.service', 'smart-tv-setup')],
            ['title' => 'App installation guidance', 'text' => 'Review safe app setup guidance for connected devices.', 'url' => route('pages.service', 'app-installation-guidance')],
            ['title' => 'Technical support in Morocco', 'text' => 'See how technical support works across Morocco.', 'url' => route('pages.service', 'technical-support-morocco')],
            ['title' => 'FAQ', 'text' => 'Read clear answers about setup, payment, and support.', 'url' => route('pages.faq')],
            ['title' => trans('legal.hub.kicker'), 'text' => 'Review privacy, refund, and service policies.', 'url' => route('pages.trust')],
            ['title' => __('site.nav.support'), 'text' => 'Contact the team for setup guidance and support.', 'url' => route('pages.contact')],
        ];
    }

    protected function serviceLinks(): array
    {
        return collect($this->servicePages())->map(fn (array $page, string $slug) => [
            'title' => $page['headline'],
            'text' => $page['meta_description'],
            'url' => route('pages.service', $slug),
        ])->values()->all();
    }
}
