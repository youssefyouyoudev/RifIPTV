<?php

namespace App\Http\Controllers;

use App\Support\SupportPlanLocalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PageController extends Controller
{
    public function services(): View { return view('pages.show', $this->payload('services')); }
    public function about(): View { return view('pages.show', $this->payload('about')); }
    public function contact(): View { return view('pages.show', $this->payload('contact')); }

    public function packages(): View
    {
        return view('pages.packages', [
            'page' => $this->packagesPage(),
            'plans' => SupportPlanLocalizer::localize(config('support_plans.plans', []), app()->getLocale()),
            'breadcrumbs' => [
                ['label' => __('site.nav.home'), 'url' => route('home')],
                ['label' => __('site.nav.pricing'), 'url' => route('pages.packages')],
            ],
        ]);
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

    protected function payload(string $key): array
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
                ['label' => $page['headline'], 'url' => route("pages.$key")],
            ],
        ];
    }

    protected function servicesPage(): array
    {
        return $this->localized([
            'en' => $this->page(
                'Device Setup Services in Morocco | Rifi Media',
                'Explore Smart TV setup, app installation help, device troubleshooting, account onboarding, and technical support services in Morocco.',
                'Services',
                'Device setup and technical support services in Morocco.',
                'Practical support for setup, troubleshooting, and follow-up',
                'Rifi Media helps clients across Morocco with Smart TV setup, app installation assistance, device troubleshooting, account onboarding, and structured post-setup support.',
                [
                    ['title' => 'Smart TV setup', 'text' => 'First-time device configuration, essential settings, and setup guidance for connected screens.'],
                    ['title' => 'App installation assistance', 'text' => 'Practical help for installing apps, organizing access, and reducing setup confusion.'],
                    ['title' => 'Device troubleshooting', 'text' => 'Support for common configuration issues, compatibility checks, and technical errors.'],
                    ['title' => 'Account onboarding help', 'text' => 'Clear help for account preparation, sign-in steps, payment review, and next actions.'],
                ],
                [
                    ['title' => 'Service area in Morocco', 'text' => 'The service is designed for clients across Morocco, with natural local relevance for Marrakech and nearby searches.'],
                    ['title' => 'How support is delivered', 'text' => 'Clients begin with a request, receive a reviewed recommendation, move through guided setup, and stay connected for follow-up.'],
                ],
                $this->faqItemsForLocale('en'),
                $this->primaryLinksForLocale('en')
            ),
            'fr' => $this->page(
                'Services de configuration d appareils au Maroc | Rifi Media',
                'Configuration Smart TV, aide a l installation d applications, depannage d appareils connectes et support technique au Maroc.',
                'Services',
                'Services de configuration d appareils et support technique au Maroc.',
                'Une aide claire pour la configuration, le depannage et le suivi',
                'Rifi Media accompagne les clients au Maroc pour la configuration Smart TV, l installation d applications, le depannage, l onboarding de compte et le suivi technique.',
                [
                    ['title' => 'Configuration Smart TV', 'text' => 'Mise en route, reglages essentiels et aide pratique pour les ecrans connectes.'],
                    ['title' => 'Aide a l installation d applications', 'text' => 'Assistance pour installer, organiser et verifier les applications utiles.'],
                    ['title' => 'Depannage d appareils', 'text' => 'Aide sur les problemes de configuration, compatibilite et erreurs techniques.'],
                    ['title' => 'Onboarding de compte', 'text' => 'Etapes plus claires pour le compte, la verification du paiement et la suite du support.'],
                ],
                [
                    ['title' => 'Zone de service au Maroc', 'text' => 'Le service couvre le Maroc avec une pertinence locale naturelle pour Marrakech et les recherches regionales.'],
                    ['title' => 'Comment le support fonctionne', 'text' => 'Chaque demande suit un parcours simple : besoin, revue, accompagnement technique, puis suivi.'],
                ],
                $this->faqItemsForLocale('fr'),
                $this->primaryLinksForLocale('fr')
            ),
            'es' => $this->page(
                'Servicios de configuracion de dispositivos en Marruecos | Rifi Media',
                'Configuracion de Smart TV, ayuda con instalacion de apps, solucion de problemas y soporte tecnico en Marruecos.',
                'Servicios',
                'Servicios de configuracion de dispositivos y soporte tecnico en Marruecos.',
                'Ayuda clara para configuracion, solucion tecnica y seguimiento',
                'Rifi Media ayuda a clientes en Marruecos con Smart TV, instalacion de apps, configuracion de cuentas, solucion de problemas y seguimiento tecnico.',
                [
                    ['title' => 'Configuracion de Smart TV', 'text' => 'Primeros ajustes, revision esencial y ayuda practica para pantallas conectadas.'],
                    ['title' => 'Ayuda con instalacion de apps', 'text' => 'Asistencia para instalar, organizar y revisar aplicaciones utiles.'],
                    ['title' => 'Solucion de problemas', 'text' => 'Apoyo con errores tecnicos, compatibilidad y problemas de configuracion.'],
                    ['title' => 'Onboarding de cuentas', 'text' => 'Un comienzo mas claro para cuentas, pagos y siguientes pasos del soporte.'],
                ],
                [
                    ['title' => 'Cobertura en Marruecos', 'text' => 'El servicio cubre Marruecos con relevancia local natural para Marrakech y otras busquedas regionales.'],
                    ['title' => 'Como funciona el soporte', 'text' => 'Cada solicitud sigue un flujo simple: necesidad, revision, guia tecnica y seguimiento.'],
                ],
                $this->faqItemsForLocale('es'),
                $this->primaryLinksForLocale('es')
            ),
            'ar' => $this->page(
                'خدمات إعداد الأجهزة والدعم التقني في المغرب | Rifi Media',
                'إعداد Smart TV، ومساعدة تثبيت التطبيقات، وحل مشاكل الأجهزة المتصلة، والدعم التقني في مختلف أنحاء المغرب.',
                'الخدمات',
                'خدمات إعداد الأجهزة والدعم التقني في المغرب.',
                'مساعدة عملية في الإعداد، وحل المشاكل، والمتابعة بعد البدء',
                'تساعد Rifi Media العملاء في المغرب على إعداد الأجهزة، وتثبيت التطبيقات، وتنظيم الحسابات، وحل المشاكل التقنية، ومتابعة الخطوات التالية بوضوح.',
                [
                    ['title' => 'إعداد Smart TV', 'text' => 'تهيئة أولية واضحة، وضبط الإعدادات الأساسية، وتجهيز الشاشة للعمل بشكل منظم.'],
                    ['title' => 'مساعدة تثبيت التطبيقات', 'text' => 'إرشاد عملي لتثبيت التطبيقات المفيدة وتنظيمها وتخفيف أي ارتباك في البداية.'],
                    ['title' => 'حل مشاكل الأجهزة', 'text' => 'مساعدة في الأخطاء الشائعة، وفحص التوافق، ومعالجة مشاكل الإعداد والاتصال.'],
                    ['title' => 'المساعدة في بدء الحساب', 'text' => 'خطوات أوضح للحساب والدفع والمتابعة التقنية بعد اختيار الخدمة المناسبة.'],
                ],
                [
                    ['title' => 'نطاق الخدمة في المغرب', 'text' => 'الخدمة موجهة للعملاء في مختلف أنحاء المغرب مع حضور محلي طبيعي لطلبات مراكش والمدن القريبة.'],
                    ['title' => 'كيف يعمل الدعم', 'text' => 'تمر كل حالة بمراحل واضحة: شرح الحاجة، مراجعة، توجيه عملي، ثم متابعة عند الحاجة.'],
                ],
                $this->faqItemsForLocale('ar'),
                $this->primaryLinksForLocale('ar')
            ),
        ]);
    }

    protected function aboutPage(): array
    {
        return $this->localized([
            'en' => $this->page('About Rifi Media | Device Setup and Technical Support in Morocco', 'Learn how Rifi Media supports clients in Morocco with structured onboarding, setup guidance, and calm technical follow-up.', __('site.nav.about'), 'Rifi Media is a Morocco-based technical support and device setup company.', 'A more transparent way to handle setup, onboarding, and support', 'The brand is built around practical setup guidance, human follow-up, and a clearer support process for Smart TV, mobile devices, tablets, and connected-device environments.', [['title' => 'Transparent workflow', 'text' => 'Clients know what happens first, what is being reviewed, and what the next step looks like.'], ['title' => 'Human support', 'text' => 'Follow-up is human-reviewed, not hidden behind vague promises or confusing status changes.'], ['title' => 'Morocco-wide service area', 'text' => 'The company supports clients across Morocco with local relevance for Marrakech without limiting the service to one city.']], [['title' => 'What we help with', 'text' => 'The team focuses on device setup, app guidance, account onboarding help, troubleshooting, and support continuity.'], ['title' => 'How trust is built', 'text' => 'Billing steps are explained clearly, support hours are visible, and public pages avoid risky content claims.']], [], $this->primaryLinksForLocale('en')),
            'fr' => $this->page('A propos de Rifi Media | Configuration et support technique au Maroc', 'Decouvrez comment Rifi Media accompagne les clients au Maroc avec un onboarding clair, une aide pratique et un support humain.', __('site.nav.about'), 'Rifi Media est une entreprise marocaine de support technique et de configuration d appareils.', 'Une approche plus claire pour la configuration, l onboarding et le support', 'La marque repose sur des etapes visibles, une aide pratique et un suivi humain pour Smart TV, mobiles, tablettes et appareils connectes.', [['title' => 'Parcours transparent', 'text' => 'Le client sait ce qui se passe, ce qui est verifie et quelle est la prochaine etape.'], ['title' => 'Support humain', 'text' => 'Le suivi reste humain et clair, sans promesses vagues ni confusion.'], ['title' => 'Presence au Maroc', 'text' => 'Le service s adresse aux clients dans tout le Maroc avec une pertinence locale pour Marrakech.']], [['title' => 'Notre champ d aide', 'text' => 'Nous aidons pour la configuration des appareils, l installation d applications, l onboarding de compte et le depannage.'], ['title' => 'Notre approche de confiance', 'text' => 'Les etapes de paiement sont expliquees clairement, les horaires de support sont visibles et le langage public reste prudent.']], [], $this->primaryLinksForLocale('fr')),
            'es' => $this->page('Sobre Rifi Media | Configuracion y soporte tecnico en Marruecos', 'Descubre como Rifi Media ayuda a clientes en Marruecos con onboarding claro, configuracion guiada y seguimiento tecnico.', __('site.nav.about'), 'Rifi Media es una empresa marroqui de soporte tecnico y configuracion de dispositivos.', 'Una forma mas clara de gestionar configuracion, onboarding y seguimiento', 'La marca se basa en pasos visibles, ayuda practica y soporte humano para Smart TV, moviles, tabletas y dispositivos conectados.', [['title' => 'Proceso transparente', 'text' => 'El cliente sabe que se revisa, que sigue y como avanza el soporte.'], ['title' => 'Soporte humano', 'text' => 'El seguimiento es claro y humano, sin promesas vagas ni mensajes confusos.'], ['title' => 'Cobertura en Marruecos', 'text' => 'El servicio cubre Marruecos con relevancia local para Marrakech.']], [['title' => 'En que ayudamos', 'text' => 'Ayudamos con configuracion de dispositivos, apps, onboarding de cuentas y solucion de problemas.'], ['title' => 'Como generamos confianza', 'text' => 'Los pasos de pago se explican claramente, los horarios estan visibles y el lenguaje del sitio es prudente.']], [], $this->primaryLinksForLocale('es')),
            'ar' => $this->page('من نحن | Rifi Media للدعم التقني في المغرب', 'تعرّف على طريقة عمل Rifi Media في إعداد الأجهزة، وبدء الحساب، والمتابعة التقنية الواضحة في مختلف أنحاء المغرب.', __('site.nav.about'), 'Rifi Media شركة مغربية للدعم التقني وإعداد الأجهزة.', 'طريقة أوضح وأكثر هدوءًا في الإعداد والدعم والمتابعة', 'تعتمد Rifi Media على خطوات واضحة، ومساعدة عملية، ومتابعة بشرية مناسبة لاحتياجات العملاء في المغرب.', [['title' => 'مسار واضح', 'text' => 'يعرف العميل ما الذي يتم مراجعته وما هي الخطوة التالية دون غموض.'], ['title' => 'دعم بشري', 'text' => 'المتابعة تتم مع أشخاص حقيقيين وبأسلوب واضح وعملي.'], ['title' => 'خدمة على مستوى المغرب', 'text' => 'الخدمة موجهة للعملاء في المغرب مع حضور محلي طبيعي في مراكش.']], [['title' => 'ما الذي نساعد فيه', 'text' => 'نركز على إعداد الأجهزة، وتثبيت التطبيقات، والمساعدة في بدء الحساب، وحل المشاكل التقنية.'], ['title' => 'كيف نبني الثقة', 'text' => 'نشرح خطوات الدفع بوضوح، ونُظهر أوقات الدعم، ونستخدم لغة آمنة ومهنية في الصفحات العامة.']], [], $this->primaryLinksForLocale('ar')),
        ]);
    }

    protected function contactPage(): array
    {
        $email = config('seo.contact_email', 'contact@rifimedia.com');
        $whatsapp = config('seo.whatsapp_url', 'https://wa.me/212663323824');
        $hours = config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00');

        return $this->localized([
            'en' => $this->page('Contact Rifi Media | Technical Support in Morocco', 'Contact Rifi Media for Smart TV setup, app guidance, billing clarification, and technical support across Morocco.', __('site.nav.support'), 'Talk to the team about setup, troubleshooting, or billing follow-up.', 'Clear contact paths for support requests in Morocco', 'Use email or WhatsApp when you need help with setup planning, technical issues, payment clarification, or service follow-up.', [['title' => 'Support email', 'text' => $email], ['title' => 'WhatsApp', 'text' => $whatsapp], ['title' => 'Support hours', 'text' => $hours], ['title' => 'Service area', 'text' => 'Morocco']], [['title' => 'Best use cases for contact', 'text' => 'Reach out for Smart TV setup, app guidance, troubleshooting, onboarding clarification, or payment follow-up.'], ['title' => 'What to expect', 'text' => 'The team reviews your request, recommends the right support level, and explains the next steps clearly.']], [], $this->primaryLinksForLocale('en')),
            'fr' => $this->page('Contacter Rifi Media | Support technique au Maroc', 'Contactez Rifi Media pour la configuration Smart TV, l aide applicative, la facturation et le support technique au Maroc.', __('site.nav.support'), 'Parlez a l equipe pour la configuration, le depannage ou le suivi du paiement.', 'Des moyens de contact clairs pour les demandes de support au Maroc', 'Utilisez l email ou WhatsApp pour preparer la configuration, clarifier un paiement ou demander une aide technique.', [['title' => 'Email support', 'text' => $email], ['title' => 'WhatsApp', 'text' => $whatsapp], ['title' => 'Horaires', 'text' => $hours], ['title' => 'Zone de service', 'text' => 'Maroc']], [['title' => 'Quand nous contacter', 'text' => 'Pour la configuration Smart TV, l installation d applications, le depannage, l onboarding et le suivi du paiement.'], ['title' => 'Ce qui se passe ensuite', 'text' => 'L equipe etudie la demande, recommande le bon niveau de support et explique les prochaines etapes.']], [], $this->primaryLinksForLocale('fr')),
            'es' => $this->page('Contacto Rifi Media | Soporte tecnico en Marruecos', 'Contacta con Rifi Media para configuracion Smart TV, ayuda con apps, aclaracion de pagos y soporte tecnico en Marruecos.', __('site.nav.support'), 'Habla con el equipo sobre configuracion, soporte tecnico o seguimiento de pago.', 'Canales claros de contacto para soporte en Marruecos', 'Usa correo o WhatsApp cuando necesites ayuda con configuracion, problemas tecnicos, pagos o seguimiento.', [['title' => 'Correo de soporte', 'text' => $email], ['title' => 'WhatsApp', 'text' => $whatsapp], ['title' => 'Horario', 'text' => $hours], ['title' => 'Area de servicio', 'text' => 'Marruecos']], [['title' => 'Cuándo contactar', 'text' => 'Para configuracion Smart TV, instalacion de apps, solucion tecnica, onboarding y revision de pago.'], ['title' => 'Qué sucede después', 'text' => 'El equipo revisa la solicitud, recomienda el nivel de soporte adecuado y explica los siguientes pasos.']], [], $this->primaryLinksForLocale('es')),
            'ar' => $this->page('تواصل مع Rifi Media | دعم تقني في المغرب', 'تواصل مع Rifi Media للمساعدة في إعداد Smart TV، وتثبيت التطبيقات، وتوضيح الدفع، والمتابعة التقنية في مختلف أنحاء المغرب.', __('site.nav.support'), 'تحدث مع الفريق بخصوص الإعداد أو المشاكل التقنية أو متابعة الدفع.', 'قنوات تواصل واضحة لطلبات الدعم في المغرب', 'يمكنك استخدام البريد أو واتساب عندما تحتاج إلى مساعدة في الإعداد، أو مشكلة تقنية، أو توضيح الدفع، أو المتابعة بعد الطلب.', [['title' => 'بريد الدعم', 'text' => $email], ['title' => 'واتساب', 'text' => $whatsapp], ['title' => 'أوقات الدعم', 'text' => $hours], ['title' => 'نطاق الخدمة', 'text' => 'المغرب']], [['title' => 'متى تتواصل معنا', 'text' => 'لإعداد Smart TV، وتثبيت التطبيقات، وحل المشاكل التقنية، والمساعدة في بدء الحساب، ومراجعة الدفع.'], ['title' => 'ماذا يحدث بعد ذلك', 'text' => 'يراجع الفريق الطلب، ويقترح مستوى الدعم المناسب، ثم يشرح الخطوات التالية بشكل واضح.']], [], $this->primaryLinksForLocale('ar')),
        ]);
    }

    protected function packagesPage(): array
    {
        return $this->localized([
            'en' => ['meta_title' => 'Support Plans | Rifi Media Morocco', 'meta_description' => 'Compare Basic, Advanced, and Premium support plans for device setup, app guidance, troubleshooting, and follow-up in Morocco.', 'kicker' => __('site.nav.pricing'), 'headline' => 'Choose the support plan that fits your setup and follow-up needs.', 'description' => 'Plans are presented as support levels with clear scope, assisted-device coverage, response handling, and follow-up duration.'],
            'fr' => ['meta_title' => 'Packages de support | Rifi Media Maroc', 'meta_description' => 'Comparez les niveaux Basic, Advanced et Premium pour la configuration, l aide applicative et le suivi technique au Maroc.', 'kicker' => __('site.nav.pricing'), 'headline' => 'Choisissez le niveau de support adapte a votre besoin.', 'description' => 'Les packages sont presentes comme des niveaux de support avec un champ d aide, un delai de reponse et une duree de suivi clairs.'],
            'es' => ['meta_title' => 'Planes de soporte | Rifi Media Marruecos', 'meta_description' => 'Compara los planes Basic, Advanced y Premium para configuracion, ayuda con apps y soporte tecnico en Marruecos.', 'kicker' => __('site.nav.pricing'), 'headline' => 'Elige el plan de soporte adecuado para tu necesidad de configuracion.', 'description' => 'Los planes se presentan como niveles de soporte con alcance, cobertura de dispositivos, respuesta y seguimiento claros.'],
            'ar' => ['meta_title' => 'خطط الدعم | Rifi Media المغرب', 'meta_description' => 'قارن بين خطط Basic وAdvanced وPremium لإعداد الأجهزة، وتثبيت التطبيقات، والدعم التقني في المغرب.', 'kicker' => __('site.nav.pricing'), 'headline' => 'اختر خطة الدعم المناسبة لاحتياجك في الإعداد والمتابعة.', 'description' => 'تُعرض الخطط كمستويات دعم واضحة من حيث نطاق الخدمة، وعدد الأجهزة، وسرعة المتابعة، وفترة الدعم.'],
        ]);
    }

    protected function faqPage(): array
    {
        return $this->localized([
            'en' => ['meta_title' => 'FAQ | Rifi Media Morocco', 'meta_description' => 'Answers about device setup, Smart TV assistance, app guidance, payment review, and technical support in Morocco.', 'kicker' => 'FAQ', 'headline' => 'Answers to common setup, onboarding, and technical support questions.', 'description' => 'This FAQ explains how Rifi Media handles device setup, payment review, troubleshooting, and follow-up support in Morocco.'],
            'fr' => ['meta_title' => 'FAQ | Rifi Media Maroc', 'meta_description' => 'Reponses sur la configuration d appareils, Smart TV, l aide applicative, le paiement et le support technique au Maroc.', 'kicker' => 'FAQ', 'headline' => 'Reponses aux questions frequentes sur la configuration et le support.', 'description' => 'Cette FAQ explique comment Rifi Media gere la configuration, le paiement, le depannage et le suivi au Maroc.'],
            'es' => ['meta_title' => 'FAQ | Rifi Media Marruecos', 'meta_description' => 'Respuestas sobre configuracion de dispositivos, Smart TV, ayuda con apps, pagos y soporte tecnico en Marruecos.', 'kicker' => 'FAQ', 'headline' => 'Respuestas a preguntas comunes sobre configuracion y soporte tecnico.', 'description' => 'Esta pagina explica como Rifi Media gestiona la configuracion, el pago, la solucion tecnica y el seguimiento en Marruecos.'],
            'ar' => ['meta_title' => 'الأسئلة الشائعة | Rifi Media', 'meta_description' => 'إجابات واضحة حول إعداد الأجهزة، ومساعدة Smart TV، وتثبيت التطبيقات، ومراجعة الدفع، والدعم التقني في المغرب.', 'kicker' => 'FAQ', 'headline' => 'إجابات على الأسئلة الشائعة حول الإعداد والدعم التقني.', 'description' => 'تشرح هذه الصفحة كيف تتعامل Rifi Media مع إعداد الأجهزة، ومراجعة الدفع، وحل المشاكل، والمتابعة التقنية في المغرب.'],
        ]);
    }

    protected function faqItems(): array
    {
        return $this->faqItemsForLocale(app()->getLocale());
    }

    protected function faqItemsForLocale(string $locale): array
    {
        return match ($locale) {
            'fr' => [
                ['q' => 'Que fait exactement Rifi Media ?', 'a' => 'Nous aidons pour la configuration Smart TV, l installation d applications, le depannage d appareils et le suivi technique.'],
                ['q' => 'Fournissez-vous du contenu media ?', 'a' => 'Non. Nous ne fournissons ni n hebergeons aucun contenu media. Nous proposons uniquement la configuration et le support technique.'],
                ['q' => 'Travaillez-vous partout au Maroc ?', 'a' => 'Oui. Le service couvre le Maroc avec une pertinence locale naturelle pour Marrakech.'],
                ['q' => 'Quels moyens de paiement sont disponibles ?', 'a' => 'Le paiement carte peut passer par Paddle et les virements locaux sont verifies manuellement par l equipe.'],
            ],
            'es' => [
                ['q' => 'Que hace exactamente Rifi Media?', 'a' => 'Ayudamos con configuracion Smart TV, instalacion de apps, solucion de problemas y seguimiento tecnico.'],
                ['q' => 'Ofrecen contenido media?', 'a' => 'No. No ofrecemos ni alojamos contenido media. Solo prestamos servicios de configuracion y soporte tecnico.'],
                ['q' => 'Trabajan en todo Marruecos?', 'a' => 'Si. El servicio cubre Marruecos con relevancia local para Marrakech.'],
                ['q' => 'Que metodos de pago hay?', 'a' => 'Los pagos con tarjeta pueden pasar por Paddle y las transferencias locales se revisan manualmente.'],
            ],
            'ar' => [
                ['q' => 'ما الذي تقدمه Rifi Media بالضبط؟', 'a' => 'نساعد في إعداد Smart TV، وتثبيت التطبيقات، وحل مشاكل الأجهزة، والمتابعة التقنية العملية.'],
                ['q' => 'هل توفرون محتوى إعلاميًا؟', 'a' => 'لا. نحن لا نوفر ولا نستضيف أي محتوى إعلامي، بل نقدم فقط خدمات الإعداد والدعم التقني.'],
                ['q' => 'هل تعملون في جميع أنحاء المغرب؟', 'a' => 'نعم. نطاق الخدمة يشمل المغرب مع حضور محلي طبيعي لطلبات مراكش.'],
                ['q' => 'ما وسائل الدفع المتاحة؟', 'a' => 'يمكن إتمام الدفع بالبطاقة عبر Paddle، كما تتم مراجعة التحويلات البنكية المحلية يدويًا من طرف الفريق.'],
            ],
            default => [
                ['q' => 'What does Rifi Media actually do?', 'a' => 'We help clients with Smart TV setup, app installation guidance, connected device troubleshooting, and practical technical support.'],
                ['q' => 'Do you provide media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
                ['q' => 'Do you work across Morocco?', 'a' => 'Yes. Our service area covers Morocco, with stronger local relevance for Marrakech.'],
                ['q' => 'What payment options are available?', 'a' => 'Card payments can be handled through Paddle, and local bank transfers are reviewed manually.'],
            ],
        };
    }

    protected function servicePages(): array
    {
        return $this->localized([
            'en' => [
                'smart-tv-setup-morocco' => $this->servicePage('Smart TV setup in Morocco', 'Smart TV setup in Morocco | Rifi Media', 'Professional Smart TV setup help in Morocco for first-time configuration, practical settings, app organization, and guided technical checks.', 'smart tv setup morocco'),
                'app-installation-help-morocco' => $this->servicePage('App installation help in Morocco', 'App installation help in Morocco | Rifi Media', 'Practical app installation help in Morocco for Smart TV, tablets, phones, and connected-device environments.', 'app installation help morocco'),
                'device-troubleshooting-morocco' => $this->servicePage('Device troubleshooting in Morocco', 'Device troubleshooting in Morocco | Rifi Media', 'Connected device troubleshooting in Morocco for Smart TV, Android boxes, tablets, phones, and setup-related technical issues.', 'device troubleshooting morocco'),
                'technical-support-marrakech' => $this->servicePage('Technical support in Marrakech', 'Technical support in Marrakech | Rifi Media', 'Local-intent technical support in Marrakech backed by Morocco-wide remote setup guidance, follow-up help, and troubleshooting.', 'technical support marrakech'),
                'account-setup-help-morocco' => $this->servicePage('Account setup help in Morocco', 'Account setup help in Morocco | Rifi Media', 'Account setup help in Morocco for first-time onboarding, sign-in steps, billing clarification, and support follow-up.', 'account setup help morocco'),
            ],
            'fr' => [
                'smart-tv-setup-morocco' => $this->servicePage('Configuration Smart TV au Maroc', 'Configuration Smart TV au Maroc | Rifi Media', 'Aide professionnelle pour la configuration Smart TV au Maroc, les reglages essentiels, l organisation des applications et les verifications techniques.', 'configuration smart tv maroc'),
                'app-installation-help-morocco' => $this->servicePage('Aide a l installation d applications au Maroc', 'Aide a l installation d applications au Maroc | Rifi Media', 'Aide pratique pour installer et organiser les applications sur Smart TV, tablette, mobile et appareils connectes au Maroc.', 'installation applications maroc'),
                'device-troubleshooting-morocco' => $this->servicePage('Depannage d appareils au Maroc', 'Depannage d appareils au Maroc | Rifi Media', 'Depannage des appareils connectes au Maroc pour Smart TV, boitiers Android, tablettes, mobiles et erreurs de configuration.', 'depannage appareils maroc'),
                'technical-support-marrakech' => $this->servicePage('Support technique a Marrakech', 'Support technique a Marrakech | Rifi Media', 'Support technique a Marrakech avec accompagnement a distance, aide de configuration et suivi pratique dans tout le Maroc.', 'support technique marrakech'),
                'account-setup-help-morocco' => $this->servicePage('Aide a la mise en route de compte au Maroc', 'Aide a la mise en route de compte au Maroc | Rifi Media', 'Aide claire pour le compte, l onboarding, les etapes de paiement et le suivi du support au Maroc.', 'onboarding compte maroc'),
            ],
            'es' => [
                'smart-tv-setup-morocco' => $this->servicePage('Configuracion Smart TV en Marruecos', 'Configuracion Smart TV en Marruecos | Rifi Media', 'Ayuda profesional para Smart TV en Marruecos con configuracion inicial, ajustes clave, organizacion de apps y revision tecnica.', 'smart tv setup morocco'),
                'app-installation-help-morocco' => $this->servicePage('Ayuda con instalacion de apps en Marruecos', 'Ayuda con instalacion de apps en Marruecos | Rifi Media', 'Ayuda practica para instalar y organizar apps en Smart TV, tabletas, moviles y dispositivos conectados en Marruecos.', 'app installation help morocco'),
                'device-troubleshooting-morocco' => $this->servicePage('Solucion tecnica de dispositivos en Marruecos', 'Solucion tecnica de dispositivos en Marruecos | Rifi Media', 'Soporte para errores de configuracion y problemas tecnicos en Smart TV, Android box, tabletas, moviles y otros equipos conectados.', 'device troubleshooting morocco'),
                'technical-support-marrakech' => $this->servicePage('Soporte tecnico en Marrakech', 'Soporte tecnico en Marrakech | Rifi Media', 'Soporte tecnico en Marrakech con ayuda remota, configuracion guiada y seguimiento practico para clientes en Marruecos.', 'technical support marrakech'),
                'account-setup-help-morocco' => $this->servicePage('Ayuda de configuracion de cuenta en Marruecos', 'Ayuda de configuracion de cuenta en Marruecos | Rifi Media', 'Ayuda clara para onboarding, acceso a cuentas, dudas de pago y seguimiento tecnico en Marruecos.', 'account setup help morocco'),
            ],
            'ar' => [
                'smart-tv-setup-morocco' => $this->servicePage('إعداد Smart TV في المغرب', 'إعداد Smart TV في المغرب | Rifi Media', 'مساعدة احترافية في إعداد Smart TV في المغرب، وضبط الإعدادات الأساسية، وتنظيم التطبيقات، وإجراء الفحوصات التقنية المهمة.', 'إعداد Smart TV في المغرب'),
                'app-installation-help-morocco' => $this->servicePage('مساعدة تثبيت التطبيقات في المغرب', 'مساعدة تثبيت التطبيقات في المغرب | Rifi Media', 'إرشاد عملي لتثبيت التطبيقات وتنظيمها على Smart TV والهواتف والأجهزة اللوحية في المغرب.', 'مساعدة تثبيت التطبيقات في المغرب'),
                'device-troubleshooting-morocco' => $this->servicePage('حل مشاكل الأجهزة في المغرب', 'حل مشاكل الأجهزة في المغرب | Rifi Media', 'دعم تقني لمعالجة مشاكل الإعداد والتوافق والأخطاء الشائعة على Smart TV والأجهزة المتصلة في المغرب.', 'حل مشاكل الأجهزة في المغرب'),
                'technical-support-marrakech' => $this->servicePage('الدعم التقني في مراكش', 'الدعم التقني في مراكش | Rifi Media', 'دعم تقني في مراكش مع متابعة عن بُعد وخدمة على مستوى المغرب في الإعداد والفحص والمتابعة.', 'الدعم التقني في مراكش'),
                'account-setup-help-morocco' => $this->servicePage('المساعدة في إعداد الحساب في المغرب', 'المساعدة في إعداد الحساب في المغرب | Rifi Media', 'مساعدة واضحة في بدء الحساب، وخطوات الدفع، والمتابعة التقنية، وتنظيم الوصول في المغرب.', 'المساعدة في إعداد الحساب في المغرب'),
            ],
        ]);
    }

    protected function servicePage(string $headline, string $metaTitle, string $metaDescription, string $topic): array
    {
        $ar = app()->isLocale('ar');
        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'kicker' => __('site.nav.features'),
            'headline' => $headline,
            'title' => $headline,
            'description' => $metaDescription,
            'cards' => [
                ['title' => $ar ? 'نطاق الخدمة' : 'Service focus', 'text' => $metaDescription],
                ['title' => $ar ? 'كيف يتم الدعم' : 'How support is delivered', 'text' => $ar ? 'تبدأ الخدمة بمراجعة الحالة، ثم توجيه عملي، ثم متابعة بشرية حسب الحاجة.' : 'Support begins with a quick review, followed by practical guidance, technical checks, and human follow-up.'],
            ],
            'sections' => [
                ['title' => $ar ? 'الارتباط المحلي' : 'Local relevance', 'text' => $ar ? 'هذه الصفحة موجهة لطلبات المغرب مع حضور طبيعي لعمليات البحث المرتبطة بمراكش.' : 'This service page targets Morocco-wide intent while keeping natural local relevance for Marrakech.'],
                ['title' => $ar ? 'ما الذي يشمله الدعم' : 'What the support includes', 'text' => $ar ? 'قد يشمل الدعم مراجعة الحالة، والإعداد، وتنظيم التطبيقات، والمتابعة بعد البدء وفق الخطة المختارة.' : 'Support may include review, setup guidance, app organization, and follow-up based on the chosen plan.'],
            ],
            'faqs' => [
                ['q' => $ar ? "هل هذه الخدمة مناسبة إذا كنت أحتاج إلى {$topic}؟" : "Is this page relevant if I need {$topic}?", 'a' => $ar ? 'نعم، هذه الصفحة تشرح نوع المساعدة التقنية المرتبطة بهذه الحاجة وكيف تبدأ بطريقة واضحة.' : 'Yes. This page explains the practical support available for this setup need and how to begin clearly.'],
                ['q' => $ar ? 'هل توفرون محتوى أو وصولًا رقميًا؟' : 'Do you provide media content or access?', 'a' => $ar ? 'لا. نحن لا نوفر ولا نستضيف محتوى إعلاميًا، بل نقدم فقط خدمات إعداد الأجهزة والدعم التقني.' : 'No. We do not provide or host media content. We only provide setup assistance and technical support.'],
            ],
            'schema_type' => 'Service',
            'links' => $this->primaryLinksForLocale(app()->getLocale()),
        ];
    }

    protected function primaryLinksForLocale(string $locale): array
    {
        return match ($locale) {
            'fr' => [['title' => 'Services', 'text' => 'Parcourir tous les services de configuration et de support.', 'url' => route('pages.services')], ['title' => 'Configuration Smart TV', 'text' => 'Comprendre l aide de configuration pour Smart TV et ecrans connectes.', 'url' => route('pages.service', 'smart-tv-setup-morocco')], ['title' => 'Aide a l installation d applications', 'text' => 'Voir comment se passe l aide de mise en place des applications.', 'url' => route('pages.service', 'app-installation-help-morocco')], ['title' => 'Support technique a Marrakech', 'text' => 'Decouvrir le support local a Marrakech et au Maroc.', 'url' => route('pages.service', 'technical-support-marrakech')], ['title' => 'FAQ', 'text' => 'Lire des reponses claires sur le support, le paiement et l onboarding.', 'url' => route('pages.faq')], ['title' => 'Centre de confiance', 'text' => 'Consulter les politiques et informations de confiance.', 'url' => route('pages.trust')], ['title' => 'Contact', 'text' => 'Parler a l equipe pour une aide de configuration ou de suivi.', 'url' => route('pages.contact')]],
            'es' => [['title' => 'Servicios', 'text' => 'Ver todos los servicios de configuracion y soporte.', 'url' => route('pages.services')], ['title' => 'Configuracion Smart TV', 'text' => 'Conocer la ayuda disponible para Smart TV y pantallas conectadas.', 'url' => route('pages.service', 'smart-tv-setup-morocco')], ['title' => 'Ayuda con apps', 'text' => 'Ver como funciona la ayuda de instalacion y organizacion de apps.', 'url' => route('pages.service', 'app-installation-help-morocco')], ['title' => 'Soporte tecnico en Marrakech', 'text' => 'Descubrir el soporte local en Marrakech y Marruecos.', 'url' => route('pages.service', 'technical-support-marrakech')], ['title' => 'FAQ', 'text' => 'Leer respuestas claras sobre soporte, pago y onboarding.', 'url' => route('pages.faq')], ['title' => 'Centro de confianza', 'text' => 'Revisar politicas e informacion de confianza.', 'url' => route('pages.trust')], ['title' => 'Contacto', 'text' => 'Hablar con el equipo para ayuda tecnica y seguimiento.', 'url' => route('pages.contact')]],
            'ar' => [['title' => 'الخدمات', 'text' => 'تصفح جميع خدمات الإعداد والإرشاد والدعم التقني.', 'url' => route('pages.services')], ['title' => 'إعداد Smart TV', 'text' => 'تعرّف على خدمة إعداد Smart TV والخطوات الأولى المناسبة.', 'url' => route('pages.service', 'smart-tv-setup-morocco')], ['title' => 'مساعدة تثبيت التطبيقات', 'text' => 'اعرف كيف تتم مساعدة تثبيت التطبيقات وتنظيمها على الجهاز.', 'url' => route('pages.service', 'app-installation-help-morocco')], ['title' => 'الدعم التقني في مراكش', 'text' => 'اكتشف خدمة الدعم التقني المرتبطة بمراكش مع تغطية على مستوى المغرب.', 'url' => route('pages.service', 'technical-support-marrakech')], ['title' => 'الأسئلة الشائعة', 'text' => 'اقرأ الإجابات الواضحة حول الإعداد والدفع والمتابعة.', 'url' => route('pages.faq')], ['title' => 'مركز الثقة', 'text' => 'راجع سياسات الخصوصية والاسترجاع وشروط الخدمة.', 'url' => route('pages.trust')], ['title' => 'التواصل', 'text' => 'تحدث مع الفريق للمساعدة في الإعداد والمتابعة التقنية.', 'url' => route('pages.contact')]],
            default => [['title' => 'Services', 'text' => 'Browse all device setup, app guidance, and technical support services.', 'url' => route('pages.services')], ['title' => 'Smart TV setup Morocco', 'text' => 'Learn about first-time Smart TV configuration and setup checks.', 'url' => route('pages.service', 'smart-tv-setup-morocco')], ['title' => 'App installation help Morocco', 'text' => 'Review practical help for installing and organizing apps on connected devices.', 'url' => route('pages.service', 'app-installation-help-morocco')], ['title' => 'Technical support Marrakech', 'text' => 'See how the local-intent support page connects Marrakech and Morocco-wide help.', 'url' => route('pages.service', 'technical-support-marrakech')], ['title' => 'FAQ', 'text' => 'Read clear answers about setup, payment review, and technical support.', 'url' => route('pages.faq')], ['title' => 'Trust center', 'text' => 'Review privacy, refund, and service-policy information.', 'url' => route('pages.trust')], ['title' => 'Contact', 'text' => 'Talk to the team for setup help, billing clarification, and support follow-up.', 'url' => route('pages.contact')]],
        };
    }

    protected function page(string $metaTitle, string $metaDescription, string $kicker, string $headline, string $title, string $description, array $cards, array $sections, array $faqs, array $links): array
    {
        return compact('metaTitle', 'metaDescription', 'kicker', 'headline', 'title', 'description', 'cards', 'sections', 'faqs', 'links') + [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
        ];
    }

    protected function localized(array $map): array
    {
        return Arr::get($map, app()->getLocale(), $map['en']);
    }
}
