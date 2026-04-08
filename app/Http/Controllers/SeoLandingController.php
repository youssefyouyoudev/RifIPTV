<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\View\View;

class SeoLandingController extends Controller
{
    public function page(string $slug): View
    {
        $page = $this->pages()[$slug] ?? abort(404);

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

    public function helpCenter(): View
    {
        return view('pages.faq', [
            'page' => $this->helpCenterPage(),
            'faqItems' => $this->faqItems(),
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => $this->tr(['en' => 'Help center', 'fr' => 'Centre d’aide', 'ar' => 'مركز المساعدة']), 'url' => route('help.center')],
            ],
        ]);
    }

    public function blog(): View
    {
        return view('pages.blog', [
            'page' => $this->blogPage(),
            'articles' => array_values($this->articles()),
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => 'Blog', 'url' => route('seo.blog')],
            ],
        ]);
    }

    public function article(string $slug): View
    {
        $article = $this->articles()[$slug] ?? abort(404);

        return view('pages.article', [
            'article' => $article,
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => 'Blog', 'url' => route('seo.blog')],
                ['label' => $article['headline'], 'url' => route('seo.blog.show', $slug)],
            ],
        ]);
    }

    protected function pages(): array
    {
        return [
            'streaming-services-maroc' => $this->makePage([
                'title' => ['en' => 'Streaming services in Morocco', 'fr' => 'Services de streaming au Maroc', 'ar' => 'خدمات البث في المغرب'],
                'description' => [
                    'en' => 'Support for streaming apps, account access, setup checks, and digital entertainment help in Morocco.',
                    'fr' => 'Support pour applications de streaming, accès aux comptes, vérifications de configuration et aide numérique au Maroc.',
                    'ar' => 'دعم تطبيقات البث، والوصول إلى الحسابات، وفحص الإعدادات، والمساعدة الرقمية داخل المغرب.',
                ],
                'kicker' => ['en' => 'Streaming services', 'fr' => 'Services de streaming', 'ar' => 'خدمات البث'],
                'body' => [
                    'en' => 'Rifi Media helps households and businesses in Morocco set up streaming apps, review account access, organize devices, and reduce technical friction.',
                    'fr' => 'Rifi Media aide les foyers et entreprises au Maroc à configurer les applications de streaming, organiser les appareils et réduire les blocages techniques.',
                    'ar' => 'تساعد Rifi Media الأسر والشركات في المغرب على إعداد تطبيقات البث، ومراجعة الوصول إلى الحسابات، وتنظيم الأجهزة، وتقليل التعقيد التقني.',
                ],
                'cards' => [
                    'en' => ['Streaming app setup', 'Account onboarding', 'Device checks', 'Follow-up support'],
                    'fr' => ['Configuration des applications', 'Onboarding des comptes', 'Vérification des appareils', 'Suivi humain'],
                    'ar' => ['إعداد التطبيقات', 'بدء الحسابات', 'فحص الأجهزة', 'متابعة لاحقة'],
                ],
            ]),
            'digital-entertainment-maroc' => $this->makePage([
                'title' => ['en' => 'Digital entertainment in Morocco', 'fr' => 'Divertissement numérique au Maroc', 'ar' => 'الترفيه الرقمي في المغرب'],
                'description' => [
                    'en' => 'Digital entertainment support in Morocco for Smart TVs, connected devices, media apps, and legal subscription onboarding.',
                    'fr' => 'Support de divertissement numérique au Maroc pour Smart TV, appareils connectés, applications média et onboarding d’abonnements légitimes.',
                    'ar' => 'دعم الترفيه الرقمي في المغرب لأجهزة Smart TV والأجهزة المتصلة والتطبيقات وبداية الاشتراكات القانونية.',
                ],
                'kicker' => ['en' => 'Digital entertainment', 'fr' => 'Divertissement numérique', 'ar' => 'الترفيه الرقمي'],
                'body' => [
                    'en' => 'The service helps clients build a cleaner digital entertainment setup at home or at work, from Smart TV screens to media devices and apps.',
                    'fr' => 'Le service aide les clients à créer un environnement numérique plus clair à la maison ou au travail, de la Smart TV aux appareils média.',
                    'ar' => 'تساعد الخدمة العملاء على بناء تجربة ترفيه رقمي أوضح في المنزل أو العمل، من شاشات Smart TV إلى أجهزة الوسائط والتطبيقات.',
                ],
                'cards' => [
                    'en' => ['Smart TV assistance', 'Media devices', 'Subscription support', 'Practical setup'],
                    'fr' => ['Assistance Smart TV', 'Appareils média', 'Support d’abonnement', 'Configuration pratique'],
                    'ar' => ['مساعدة Smart TV', 'أجهزة الوسائط', 'دعم الاشتراك', 'إعداد عملي'],
                ],
            ]),
            'smart-tv-setup-maroc' => $this->makePage([
                'title' => ['en' => 'Smart TV setup in Morocco', 'fr' => 'Configuration Smart TV au Maroc', 'ar' => 'إعداد Smart TV في المغرب'],
                'description' => [
                    'en' => 'Smart TV setup in Morocco with installation, settings review, account help, and practical technical support.',
                    'fr' => 'Configuration Smart TV au Maroc avec installation, réglages, aide au compte et support pratique.',
                    'ar' => 'إعداد Smart TV في المغرب مع التثبيت، ومراجعة الإعدادات، والمساعدة في الحساب، والدعم العملي.',
                ],
                'kicker' => ['en' => 'Smart TV setup', 'fr' => 'Configuration Smart TV', 'ar' => 'إعداد Smart TV'],
                'body' => [
                    'en' => 'We help clients configure Smart TVs, install useful apps, organize accounts, and fix first-time setup issues with clearer support.',
                    'fr' => 'Nous aidons les clients à configurer leur Smart TV, installer les applications utiles, organiser les comptes et corriger les blocages initiaux.',
                    'ar' => 'نساعد العملاء في تجهيز أجهزة Smart TV وتثبيت التطبيقات المفيدة وتنظيم الحسابات وحل مشاكل البداية بدعم أوضح.',
                ],
                'cards' => [
                    'en' => ['First-time setup', 'App installation', 'Account help', 'Practical fixes'],
                    'fr' => ['Première configuration', 'Installation des apps', 'Aide au compte', 'Corrections pratiques'],
                    'ar' => ['إعداد أولي', 'تثبيت التطبيقات', 'مساعدة في الحساب', 'حلول عملية'],
                ],
            ]),
            'media-solutions-maroc' => $this->makePage([
                'title' => ['en' => 'Media solutions in Morocco', 'fr' => 'Solutions média au Maroc', 'ar' => 'حلول الوسائط في المغرب'],
                'description' => [
                    'en' => 'Media solutions in Morocco for connected devices, digital entertainment systems, and home setup support.',
                    'fr' => 'Solutions média au Maroc pour appareils connectés, systèmes de divertissement numérique et support de configuration.',
                    'ar' => 'حلول الوسائط في المغرب للأجهزة المتصلة وأنظمة الترفيه الرقمي ودعم الإعداد المنزلي.',
                ],
                'kicker' => ['en' => 'Media solutions', 'fr' => 'Solutions média', 'ar' => 'حلول الوسائط'],
                'body' => [
                    'en' => 'Rifi Media helps households and businesses plan media devices, connected screens, streaming apps, and practical digital setup support.',
                    'fr' => 'Rifi Media aide les foyers et entreprises à organiser les appareils média, les écrans connectés et les applications avec un support pratique.',
                    'ar' => 'تساعد Rifi Media الأفراد والشركات في تنظيم أجهزة الوسائط والشاشات المتصلة والتطبيقات مع دعم عملي واضح.',
                ],
                'cards' => [
                    'en' => ['Connected screens', 'Media devices', 'Apps and access', 'Technical support'],
                    'fr' => ['Écrans connectés', 'Appareils média', 'Applications et accès', 'Support technique'],
                    'ar' => ['شاشات متصلة', 'أجهزة وسائط', 'تطبيقات ووصول', 'دعم تقني'],
                ],
            ]),
            'streaming-support-nador' => $this->makePage([
                'title' => ['en' => 'Streaming support in Nador', 'fr' => 'Support streaming à Nador', 'ar' => 'دعم البث في الناظور'],
                'description' => [
                    'en' => 'Local streaming support in Nador for app setup, account onboarding, and practical device troubleshooting.',
                    'fr' => 'Support streaming local à Nador pour applications, comptes et dépannage pratique.',
                    'ar' => 'دعم بث محلي في الناظور من أجل التطبيقات والحسابات وحل مشكلات الأجهزة بشكل عملي.',
                ],
                'kicker' => ['en' => 'Local support', 'fr' => 'Support local', 'ar' => 'دعم محلي'],
                'body' => [
                    'en' => 'Clients in Nador can use Rifi Media for local-relevance setup help, streaming app questions, onboarding guidance, and calm troubleshooting.',
                    'fr' => 'Les clients à Nador peuvent faire appel à Rifi Media pour une aide locale sur les applications، l’onboarding et le dépannage.',
                    'ar' => 'يمكن لعملاء الناظور الاعتماد على Rifi Media من أجل دعم محلي في التطبيقات وبدء الحسابات وحل المشكلات التقنية.',
                ],
                'cards' => [
                    'en' => ['Local setup help', 'App review', 'Account access', 'Follow-up support'],
                    'fr' => ['Aide locale', 'Revue des apps', 'Accès aux comptes', 'Suivi humain'],
                    'ar' => ['مساعدة محلية', 'مراجعة التطبيقات', 'الوصول إلى الحسابات', 'متابعة لاحقة'],
                ],
            ]),
            'smart-tv-setup-nador' => $this->makePage([
                'title' => ['en' => 'Smart TV setup in Nador', 'fr' => 'Configuration Smart TV à Nador', 'ar' => 'إعداد Smart TV في الناظور'],
                'description' => [
                    'en' => 'Smart TV setup in Nador with app installation, settings review, and practical technical help.',
                    'fr' => 'Configuration Smart TV à Nador avec installation des apps, revue des réglages et aide pratique.',
                    'ar' => 'إعداد Smart TV في الناظور مع تثبيت التطبيقات ومراجعة الإعدادات والمساعدة التقنية العملية.',
                ],
                'kicker' => ['en' => 'Local Smart TV setup', 'fr' => 'Configuration locale Smart TV', 'ar' => 'إعداد Smart TV محلي'],
                'body' => [
                    'en' => 'Rifi Media helps homes in Nador set up Smart TVs, organize apps, review sign-in flow, and smooth out first-time usage problems.',
                    'fr' => 'Rifi Media aide les foyers à Nador à configurer leur Smart TV, organiser les apps et améliorer la première utilisation.',
                    'ar' => 'تساعد Rifi Media المنازل في الناظور في تجهيز Smart TV وتنظيم التطبيقات وتوضيح تسجيل الدخول وتسهيل أول استخدام.',
                ],
                'cards' => [
                    'en' => ['TV setup guidance', 'App organization', 'Sign-in help', 'Technical checks'],
                    'fr' => ['Guidage TV', 'Organisation des apps', 'Aide à la connexion', 'Vérifications techniques'],
                    'ar' => ['إرشاد التلفاز', 'تنظيم التطبيقات', 'مساعدة تسجيل الدخول', 'فحوصات تقنية'],
                ],
            ]),
            'device-configuration-morocco' => $this->makePage([
                'title' => ['en' => 'Device configuration in Morocco', 'fr' => 'Configuration d’appareils au Maroc', 'ar' => 'إعداد الأجهزة في المغرب'],
                'description' => [
                    'en' => 'Device configuration service in Morocco for Smart TVs, media boxes, tablets, and app-ready digital setups.',
                    'fr' => 'Service de configuration d’appareils au Maroc pour Smart TV, media box, tablettes et configuration numérique.',
                    'ar' => 'خدمة إعداد الأجهزة في المغرب للتلفاز الذكي وmedia box والأجهزة اللوحية وبيئات الاستخدام الرقمية.',
                ],
                'kicker' => ['en' => 'Device configuration', 'fr' => 'Configuration d’appareils', 'ar' => 'إعداد الأجهزة'],
                'body' => [
                    'en' => 'Rifi Media helps configure everyday digital devices so they feel easier to use, more stable, and better organized from the beginning.',
                    'fr' => 'Rifi Media aide à configurer les appareils du quotidien pour une utilisation plus simple, plus stable et mieux organisée.',
                    'ar' => 'تساعد Rifi Media في إعداد الأجهزة الرقمية اليومية حتى تصبح أسهل استخدامًا وأكثر استقرارًا وتنظيمًا منذ البداية.',
                ],
                'cards' => [
                    'en' => ['TV and media boxes', 'Tablets and mobile', 'Account access', 'Ongoing support'],
                    'fr' => ['TV et media box', 'Tablettes et mobile', 'Accès aux comptes', 'Support continu'],
                    'ar' => ['تلفاز وmedia box', 'أجهزة لوحية وهواتف', 'الوصول إلى الحسابات', 'دعم مستمر'],
                ],
            ]),
            'technical-support-morocco' => $this->makePage([
                'title' => ['en' => 'Technical support in Morocco', 'fr' => 'Support technique au Maroc', 'ar' => 'الدعم التقني في المغرب'],
                'description' => [
                    'en' => 'Technical support in Morocco for Smart TVs, streaming apps, account issues, and connected device troubleshooting.',
                    'fr' => 'Support technique au Maroc pour Smart TV, applications, comptes et dépannage des appareils connectés.',
                    'ar' => 'دعم تقني في المغرب لأجهزة Smart TV وتطبيقات البث والحسابات ومشكلات الأجهزة المتصلة.',
                ],
                'kicker' => ['en' => 'Technical support', 'fr' => 'Support technique', 'ar' => 'الدعم التقني'],
                'body' => [
                    'en' => 'The support flow covers setup clarification, app questions, account access, device checks, and practical follow-up when something stops working as expected.',
                    'fr' => 'Le support couvre les étapes de configuration, les questions d’apps, les comptes, les vérifications d’appareils et le suivi pratique.',
                    'ar' => 'يشمل الدعم توضيح خطوات الإعداد وأسئلة التطبيقات والوصول إلى الحسابات وفحص الأجهزة والمتابعة العملية عند وجود مشكلة.',
                ],
                'cards' => [
                    'en' => ['Setup clarification', 'App help', 'Account help', 'Practical troubleshooting'],
                    'fr' => ['Clarification du setup', 'Aide aux apps', 'Aide aux comptes', 'Dépannage pratique'],
                    'ar' => ['توضيح الإعداد', 'مساعدة التطبيقات', 'مساعدة الحساب', 'حلول تقنية عملية'],
                ],
            ]),
        ];
    }

    protected function articles(): array
    {
        $articles = [
            'best-streaming-setup-morocco' => [
                'title' => ['en' => 'Best streaming setup in Morocco', 'fr' => 'Le meilleur setup streaming au Maroc', 'ar' => 'أفضل إعداد بث في المغرب'],
                'description' => [
                    'en' => 'How to create a smoother streaming setup at home with the right Smart TV, apps, network habits, and account organization.',
                    'fr' => 'Comment créer une expérience streaming plus fluide à la maison avec la bonne Smart TV, les bonnes apps et un meilleur réseau.',
                    'ar' => 'كيف تبني تجربة بث أكثر سلاسة في المنزل من خلال اختيار Smart TV المناسب والتطبيقات والشبكة وتنظيم الحسابات.',
                ],
            ],
            'how-to-choose-smart-tv-service' => [
                'title' => ['en' => 'How to choose a smart TV service', 'fr' => 'Comment choisir un service Smart TV', 'ar' => 'كيف تختار خدمة Smart TV المناسبة'],
                'description' => [
                    'en' => 'A practical guide to choosing a Smart TV setup and support service in Morocco without confusion or risky promises.',
                    'fr' => 'Guide pratique pour choisir un service Smart TV au Maroc sans confusion ni promesses floues.',
                    'ar' => 'دليل عملي لاختيار خدمة Smart TV في المغرب دون غموض أو وعود مبالغ فيها.',
                ],
            ],
            'streaming-vs-traditional-tv-morocco' => [
                'title' => ['en' => 'Streaming vs traditional TV in Morocco', 'fr' => 'Streaming ou télévision traditionnelle au Maroc', 'ar' => 'البث أم التلفاز التقليدي في المغرب'],
                'description' => [
                    'en' => 'Compare streaming and traditional TV setup needs in Morocco, from convenience to flexibility and support needs.',
                    'fr' => 'Comparez streaming et télévision traditionnelle au Maroc selon la flexibilité, la simplicité et le besoin de support.',
                    'ar' => 'قارن بين البث والتلفاز التقليدي في المغرب من حيث السهولة والمرونة واحتياج الدعم.',
                ],
            ],
            'fix-buffering-improve-streaming-quality' => [
                'title' => ['en' => 'Fix buffering and improve streaming quality', 'fr' => 'Réduire le buffering et améliorer la qualité du streaming', 'ar' => 'حل مشكلة التقطيع وتحسين جودة البث'],
                'description' => [
                    'en' => 'Simple ways to reduce buffering and improve streaming quality at home through better setup habits.',
                    'fr' => 'Des méthodes simples pour réduire le buffering et améliorer la qualité du streaming à la maison.',
                    'ar' => 'طرق بسيطة لتقليل التقطيع وتحسين جودة البث في المنزل من خلال إعداد أفضل.',
                ],
            ],
            'guide-digital-entertainment-systems' => [
                'title' => ['en' => 'Guide to digital entertainment systems', 'fr' => 'Guide des systèmes de divertissement numérique', 'ar' => 'دليل أنظمة الترفيه الرقمي'],
                'description' => [
                    'en' => 'A beginner-friendly guide to Smart TVs, media devices, streaming apps, subscription access, and setup support.',
                    'fr' => 'Guide simple pour comprendre Smart TV, appareils média, applications, abonnements et support de configuration.',
                    'ar' => 'دليل مبسط لفهم Smart TV وأجهزة الوسائط والتطبيقات والاشتراكات والدعم التقني.',
                ],
            ],
        ];

        return collect($articles)->mapWithKeys(function (array $article, string $slug) {
            $headline = $this->pick($article['title']);
            $description = $this->pick($article['description']);

            return [$slug => [
                'slug' => $slug,
                'meta_title' => "{$headline} | Rifi Media",
                'meta_description' => $description,
                'headline' => $headline,
                'description' => $description,
                'sections' => [
                    $description,
                    $this->tr([
                        'en' => 'This article keeps the focus on practical setup, device behavior, account flow, and a calmer user experience.',
                        'fr' => 'Cet article reste centré sur la configuration pratique, le comportement des appareils, le flux des comptes et une expérience plus fluide.',
                        'ar' => 'يركز هذا المقال على الإعداد العملي وسلوك الأجهزة ومسار الحسابات وتجربة استخدام أكثر هدوءًا.',
                    ]),
                    $this->tr([
                        'en' => 'Each guide links back to support pages, trust pages, and the contact flow so readers can move from learning to action.',
                        'fr' => 'Chaque guide renvoie vers les pages de support, les pages de confiance et le contact afin de transformer la lecture en action.',
                        'ar' => 'يرتبط كل دليل بصفحات الدعم والثقة والتواصل حتى تتحول القراءة إلى خطوة عملية واضحة.',
                    ]),
                ],
                'faqs' => $this->faqItems(),
                'links' => $this->relatedLinks(),
            ]];
        })->all();
    }

    protected function makePage(array $definition): array
    {
        $title = $this->pick($definition['title']);
        $description = $this->pick($definition['description']);

        return [
            'meta_title' => "{$title} | Rifi Media",
            'meta_description' => $description,
            'kicker' => $this->pick($definition['kicker']),
            'title' => $title,
            'headline' => $title,
            'description' => $this->pick($definition['body']),
            'cards' => collect($this->pick($definition['cards']))->map(fn (string $item) => [
                'title' => $item,
                'text' => $item,
            ])->all(),
            'sections' => [
                [
                    'title' => $this->tr(['en' => 'Service area', 'fr' => 'Zone de service', 'ar' => 'نطاق الخدمة']),
                    'text' => $this->tr([
                        'en' => 'Morocco with natural local relevance for Nador and support-ready service for connected devices.',
                        'fr' => 'Maroc avec une pertinence locale naturelle pour Nador et un service prêt pour les appareils connectés.',
                        'ar' => 'المغرب مع حضور محلي طبيعي في الناظور وخدمة جاهزة لدعم الأجهزة المتصلة.',
                    ]),
                ],
                [
                    'title' => $this->tr(['en' => 'Trust note', 'fr' => 'Message de confiance', 'ar' => 'رسالة ثقة']),
                    'text' => $this->tr([
                        'en' => 'The service focuses on setup, apps, accounts, and technical support only. It does not provide hosted content.',
                        'fr' => 'Le service couvre uniquement la configuration, les applications, les comptes et le support technique. Il ne fournit pas de contenu hébergé.',
                        'ar' => 'تقتصر الخدمة على الإعداد والتطبيقات والحسابات والدعم التقني فقط. ولا تقدم أي محتوى مستضاف.',
                    ]),
                ],
            ],
            'faqs' => $this->faqItems(),
            'links' => $this->relatedLinks(),
            'schema_type' => 'Service',
        ];
    }

    protected function faqItems(): array
    {
        return match (app()->getLocale()) {
            'fr' => [
                ['q' => 'Que fait exactement Rifi Media ?', 'a' => 'Rifi Media aide à configurer les appareils, les applications, les comptes et le support technique au Maroc.'],
                ['q' => 'Fournissez-vous du contenu ?', 'a' => 'Non. Le service couvre uniquement l’aide à la mise en route, aux comptes, aux applications et au support.'],
                ['q' => 'Travaillez-vous partout au Maroc ?', 'a' => 'Oui. Le service est disponible au Maroc avec une pertinence locale naturelle pour Nador.'],
                    ['q' => 'Comment fonctionnent les paiements ?', 'a' => 'Les paiements internationaux passent par Paddle et les virements locaux ainsi que le cash sont revus manuellement.'],
            ],
            'ar' => [
                ['q' => 'ما الذي تقدمه Rifi Media؟', 'a' => 'تساعد Rifi Media في إعداد الأجهزة والتطبيقات والحسابات وتقديم الدعم التقني داخل المغرب.'],
                ['q' => 'هل توفّرون محتوى؟', 'a' => 'لا. تغطي الخدمة الإعداد والحسابات والتطبيقات والدعم فقط.'],
                ['q' => 'هل تعملون في كل المغرب؟', 'a' => 'نعم. الخدمة متاحة في المغرب مع حضور محلي طبيعي في الناظور.'],
                    ['q' => 'كيف تتم المدفوعات؟', 'a' => 'تتم المدفوعات الدولية عبر Paddle وتراجع التحويلات المحلية والدفع النقدي يدويًا.'],
            ],
            default => [
                ['q' => 'What does Rifi Media do?', 'a' => 'Rifi Media helps with device setup, app guidance, account support, and practical technical troubleshooting in Morocco.'],
                ['q' => 'Do you provide content?', 'a' => 'No. The service focuses only on setup, onboarding, apps, and technical support.'],
                ['q' => 'Do you work across Morocco?', 'a' => 'Yes. The service is available across Morocco with natural local relevance for Nador.'],
                    ['q' => 'How are payments handled?', 'a' => 'International card payments use Paddle while local transfers and cash payments are reviewed manually.'],
            ],
        };
    }

    protected function relatedLinks(): array
    {
        return [
            [
                'title' => $this->tr(['en' => 'Services', 'fr' => 'Services', 'ar' => 'الخدمات']),
                'text' => $this->tr(['en' => 'Review the core setup and support services.', 'fr' => 'Consultez les services de configuration et de support.', 'ar' => 'راجع خدمات الإعداد والدعم الأساسية.']),
                'url' => route('pages.services'),
            ],
            [
                'title' => $this->tr(['en' => 'Packages', 'fr' => 'Forfaits', 'ar' => 'الباقات']),
                'text' => $this->tr(['en' => 'Compare support levels and follow-up depth.', 'fr' => 'Comparez les niveaux de support et de suivi.', 'ar' => 'قارن بين مستويات الدعم وعمق المتابعة.']),
                'url' => route('pages.packages'),
            ],
            [
                'title' => $this->tr(['en' => 'Contact', 'fr' => 'Contact', 'ar' => 'التواصل']),
                'text' => $this->tr(['en' => 'Talk to the team before you begin.', 'fr' => 'Parlez à l’équipe avant de commencer.', 'ar' => 'تحدث مع الفريق قبل أن تبدأ.']),
                'url' => route('pages.contact'),
            ],
            [
                'title' => $this->tr(['en' => 'Trust center', 'fr' => 'Centre de confiance', 'ar' => 'مركز الثقة']),
                'text' => $this->tr(['en' => 'Review privacy, refund, security, and billing policies.', 'fr' => 'Consultez les politiques de confidentialité, remboursement, sécurité et facturation.', 'ar' => 'راجع سياسات الخصوصية والاسترجاع والأمان والفوترة.']),
                'url' => route('pages.trust'),
            ],
        ];
    }

    protected function helpCenterPage(): array
    {
        return [
            'meta_title' => $this->tr(['en' => 'Help center | Rifi Media', 'fr' => 'Centre d’aide | Rifi Media', 'ar' => 'مركز المساعدة | Rifi Media']),
            'meta_description' => $this->tr([
                'en' => 'Clear answers about setup, accounts, apps, payments, and support in Morocco.',
                'fr' => 'Réponses claires sur le setup, les comptes, les applications, les paiements et le support au Maroc.',
                'ar' => 'إجابات واضحة حول الإعداد والحسابات والتطبيقات والدفع والدعم داخل المغرب.',
            ]),
            'kicker' => $this->tr(['en' => 'Help center', 'fr' => 'Centre d’aide', 'ar' => 'مركز المساعدة']),
            'title' => $this->tr([
                'en' => 'Clear answers to setup and support questions',
                'fr' => 'Des réponses claires aux questions de setup et de support',
                'ar' => 'إجابات واضحة لأسئلة الإعداد والدعم',
            ]),
            'headline' => $this->tr(['en' => 'Help center', 'fr' => 'Centre d’aide', 'ar' => 'مركز المساعدة']),
            'description' => $this->tr([
                'en' => 'This page collects the most useful questions about Smart TVs, apps, accounts, payments, and follow-up.',
                'fr' => 'Cette page regroupe les questions les plus utiles sur Smart TV, les applications, les comptes, les paiements et le suivi.',
                'ar' => 'تجمع هذه الصفحة أهم الأسئلة حول Smart TV والتطبيقات والحسابات والدفع والمتابعة.',
            ]),
        ];
    }

    protected function blogPage(): array
    {
        return [
            'meta_title' => $this->tr(['en' => 'Blog | Rifi Media', 'fr' => 'Blog | Rifi Media', 'ar' => 'المدونة | Rifi Media']),
            'meta_description' => $this->tr([
                'en' => 'People-first guides about Smart TV setup, streaming support, device configuration, and digital entertainment in Morocco.',
                'fr' => 'Guides utiles sur Smart TV, le support streaming, la configuration des appareils et le divertissement numérique au Maroc.',
                'ar' => 'أدلة مفيدة حول Smart TV ودعم البث وإعداد الأجهزة والترفيه الرقمي في المغرب.',
            ]),
            'kicker' => $this->tr(['en' => 'Blog', 'fr' => 'Blog', 'ar' => 'المدونة']),
            'headline' => $this->tr([
                'en' => 'Useful setup and support guides for Morocco',
                'fr' => 'Guides utiles de setup et de support pour le Maroc',
                'ar' => 'أدلة مفيدة للإعداد والدعم داخل المغرب',
            ]),
            'description' => $this->tr([
                'en' => 'Read practical articles about Smart TVs, connected devices, apps, support flow, and smoother digital setup habits.',
                'fr' => 'Lisez des articles pratiques sur les Smart TV, les appareils connectés, les applications et les bonnes pratiques de setup.',
                'ar' => 'اقرأ مقالات عملية حول Smart TV والأجهزة المتصلة والتطبيقات ومسار الدعم وعادات الإعداد الأفضل.',
            ]),
        ];
    }

    protected function pick(array $values): mixed
    {
        return Arr::get($values, app()->getLocale(), $values['en'] ?? null);
    }

    protected function tr(array $values): string
    {
        return (string) $this->pick($values);
    }
}
