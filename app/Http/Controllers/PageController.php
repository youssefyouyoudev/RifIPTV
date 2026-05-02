<?php

namespace App\Http\Controllers;

use App\Support\SupportPlanCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PageController extends Controller
{
    public function services(): View
    {
        return view('pages.show', $this->payload('services'));
    }

    public function about(): View
    {
        return view('pages.show', $this->payload('about'));
    }

    public function contact(): View
    {
        return view('pages.show', $this->payload('contact'));
    }

    public function packages(): View
    {
        return view('pages.packages', [
            'page' => $this->packagesPage(),
            'plans' => SupportPlanCatalog::forStorefront(app()->getLocale()),
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
                ['label' => $page['headline'], 'url' => route("pages.{$key}")],
            ],
        ];
    }

    protected function servicesPage(): array
    {
        return $this->localized([
            'en' => $this->page(
                'Device setup services in Morocco | Rifi Media',
                'Explore device setup, Smart TV setup, app installation help, troubleshooting, and technical support services in Morocco.',
                'Services',
                'Device setup and technical support services in Morocco.',
                'Practical help for setup, troubleshooting, and follow-up support',
                'Rifi Media helps clients across Morocco with Smart TV setup, app installation assistance, connected device troubleshooting, account onboarding help, and calm technical follow-up.',
                [
                    ['title' => 'Smart TV setup', 'text' => 'Guided first-time setup, core settings, and device organization for connected screens.'],
                    ['title' => 'App installation assistance', 'text' => 'Help with installing useful apps, checking login flow, and reducing setup friction.'],
                    ['title' => 'Troubleshooting', 'text' => 'Support for device configuration issues, compatibility checks, and practical fixes.'],
                    ['title' => 'Follow-up support', 'text' => 'Clear post-setup guidance so the client knows what to do next and who to contact.'],
                ],
                [
                    ['title' => 'Service area', 'text' => 'The service is available across Morocco, with natural local relevance for Marrakech and nearby city searches.'],
                    ['title' => 'Support approach', 'text' => 'Clients move through request, review, guided setup, and follow-up instead of a vague one-step sales flow.'],
                ],
                $this->faqItemsForLocale('en'),
                $this->primaryLinksForLocale('en')
            ),
            'fr' => $this->page(
                'Services de configuration d appareils au Maroc | Rifi Media',
                'Configuration Smart TV, aide a l installation d applications, depannage et support technique au Maroc.',
                'Services',
                'Services de configuration d appareils et de support technique au Maroc.',
                'Une aide pratique pour la configuration, le depannage et le suivi',
                'Rifi Media accompagne les clients au Maroc avec la configuration Smart TV, l aide a l installation d applications, le depannage et le suivi technique.',
                [
                    ['title' => 'Configuration Smart TV', 'text' => 'Mise en route guidee, reglages essentiels et organisation pratique pour les ecrans connectes.'],
                    ['title' => 'Aide a l installation d applications', 'text' => 'Assistance pour installer les applications utiles, verifier les connexions et simplifier le demarrage.'],
                    ['title' => 'Depannage', 'text' => 'Aide sur les erreurs de configuration, la compatibilite et les problemes techniques du quotidien.'],
                    ['title' => 'Suivi technique', 'text' => 'Un accompagnement plus clair apres la mise en route pour garder un point de contact utile.'],
                ],
                [
                    ['title' => 'Zone de service', 'text' => 'Le service couvre le Maroc avec une pertinence locale naturelle pour Marrakech et les recherches regionales.'],
                    ['title' => 'Methode de support', 'text' => 'Le client suit un parcours simple : besoin, revue, guidage technique, puis suivi humain.'],
                ],
                $this->faqItemsForLocale('fr'),
                $this->primaryLinksForLocale('fr')
            ),
            'es' => $this->page(
                'Servicios de configuracion de dispositivos en Marruecos | Rifi Media',
                'Configuracion de Smart TV, ayuda con apps, solucion de problemas y soporte tecnico en Marruecos.',
                'Servicios',
                'Servicios de configuracion de dispositivos y soporte tecnico en Marruecos.',
                'Ayuda practica para configuracion, solucion tecnica y seguimiento',
                'Rifi Media ayuda a clientes en Marruecos con configuracion de Smart TV, instalacion de apps, solucion de problemas y seguimiento tecnico.',
                [
                    ['title' => 'Configuracion de Smart TV', 'text' => 'Puesta en marcha guiada, ajustes esenciales y organizacion practica para pantallas conectadas.'],
                    ['title' => 'Ayuda con instalacion de apps', 'text' => 'Asistencia para instalar apps utiles, revisar accesos y simplificar el primer uso.'],
                    ['title' => 'Solucion de problemas', 'text' => 'Apoyo con compatibilidad, errores de configuracion y problemas tecnicos comunes.'],
                    ['title' => 'Seguimiento tecnico', 'text' => 'Un seguimiento claro para que el cliente sepa que hacer despues del soporte inicial.'],
                ],
                [
                    ['title' => 'Cobertura en Marruecos', 'text' => 'El servicio cubre Marruecos con relevancia local natural para Marrakech y otras busquedas regionales.'],
                    ['title' => 'Metodo de soporte', 'text' => 'Cada solicitud sigue un flujo claro: necesidad, revision, guia tecnica y seguimiento.'],
                ],
                $this->faqItemsForLocale('es'),
                $this->primaryLinksForLocale('es')
            ),
            'ar' => $this->page(
                'خدمات إعداد الأجهزة والدعم التقني في المغرب | Rifi Media',
                'إعداد التلفاز الذكي، والمساعدة في تثبيت التطبيقات، وحل المشكلات التقنية، والدعم الفني في مختلف أنحاء المغرب.',
                'الخدمات',
                'خدمات إعداد الأجهزة والدعم التقني في المغرب.',
                'مساعدة عملية في الإعداد، وحل المشكلات، والمتابعة بعد البداية',
                'تساعد Rifi Media العملاء في المغرب على إعداد الأجهزة، وتثبيت التطبيقات، وتنظيم الحسابات، وحل المشكلات التقنية، ومتابعة الخطوات التالية بوضوح.',
                [
                    ['title' => 'إعداد التلفاز الذكي', 'text' => 'تهيئة أولية واضحة، وضبط الإعدادات الأساسية، وتجهيز الشاشة للعمل بشكل منظم.'],
                    ['title' => 'مساعدة تثبيت التطبيقات', 'text' => 'إرشاد عملي لتثبيت التطبيقات المفيدة وتنظيمها وتخفيف أي ارتباك في البداية.'],
                    ['title' => 'حل المشكلات التقنية', 'text' => 'مساعدة في الأخطاء الشائعة، وفحص التوافق، ومعالجة مشاكل الإعداد والاتصال.'],
                    ['title' => 'متابعة تقنية', 'text' => 'خطوات أوضح بعد الإعداد حتى يبقى العميل على معرفة بما يلي ومع من يتواصل.'],
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
            'en' => $this->page(
                'About Rifi Media | Device setup and technical support in Morocco',
                'Learn how Rifi Media helps clients in Morocco with setup guidance, onboarding help, and structured technical support.',
                __('site.nav.about'),
                'Rifi Media is a Morocco-based technical support and device setup company.',
                'A clearer approach to setup, onboarding, and follow-up support',
                'Rifi Media is built around practical setup guidance, honest communication, and structured support for Smart TVs, tablets, mobile devices, and connected-device environments.',
                [
                    ['title' => 'Transparent workflow', 'text' => 'Clients know what is being reviewed, what happens next, and where to get practical help.'],
                    ['title' => 'Human-reviewed support', 'text' => 'The service is not hidden behind vague automation. A real team reviews the request and follow-up.'],
                    ['title' => 'Morocco-wide relevance', 'text' => 'The service supports clients across Morocco with natural local relevance for Marrakech.'],
                ],
                [
                    ['title' => 'What we focus on', 'text' => 'Device setup, app guidance, account onboarding help, troubleshooting, and post-purchase support.'],
                    ['title' => 'How trust is built', 'text' => 'Billing steps are explained clearly, support hours are visible, and public pages avoid risky or misleading claims.'],
                ],
                [],
                $this->primaryLinksForLocale('en')
            ),
            'fr' => $this->page(
                'A propos de Rifi Media | Configuration et support technique au Maroc',
                'Decouvrez comment Rifi Media aide les clients au Maroc avec un onboarding clair, une aide pratique et un support structure.',
                __('site.nav.about'),
                'Rifi Media est une entreprise marocaine de support technique et de configuration d appareils.',
                'Une approche plus claire pour la configuration, l onboarding et le suivi',
                'Rifi Media repose sur des etapes visibles, une aide pratique et un suivi humain pour les ecrans connectes, les tablettes, les mobiles et les appareils relies.',
                [
                    ['title' => 'Parcours transparent', 'text' => 'Le client sait ce qui est verifie, ce qui suit et ou trouver la bonne aide.'],
                    ['title' => 'Support humain', 'text' => 'Le support reste humain, clair et pratique, sans langage confus ni promesses vagues.'],
                    ['title' => 'Presence au Maroc', 'text' => 'Le service couvre le Maroc avec une pertinence locale naturelle pour Marrakech.'],
                ],
                [
                    ['title' => 'Notre champ d aide', 'text' => 'Configuration d appareils, aide applicative, onboarding de compte, depannage et suivi.'],
                    ['title' => 'Comment nous inspirons confiance', 'text' => 'Les etapes de paiement sont expliquees, les horaires sont visibles et le ton reste prudent.'],
                ],
                [],
                $this->primaryLinksForLocale('fr')
            ),
            'es' => $this->page(
                'Sobre Rifi Media | Configuracion y soporte tecnico en Marruecos',
                'Conoce como Rifi Media ayuda a clientes en Marruecos con configuracion guiada, onboarding y soporte tecnico claro.',
                __('site.nav.about'),
                'Rifi Media es una empresa marroqui de soporte tecnico y configuracion de dispositivos.',
                'Una forma mas clara de gestionar configuracion, onboarding y seguimiento',
                'Rifi Media se basa en pasos visibles, ayuda practica y seguimiento humano para Smart TV, tabletas, moviles y entornos de dispositivos conectados.',
                [
                    ['title' => 'Proceso transparente', 'text' => 'El cliente entiende que se revisa, que sigue y donde encontrar ayuda util.'],
                    ['title' => 'Soporte humano', 'text' => 'El seguimiento sigue siendo humano y claro, sin promesas vagas ni mensajes sospechosos.'],
                    ['title' => 'Cobertura en Marruecos', 'text' => 'El servicio cubre Marruecos con relevancia local natural para Marrakech.'],
                ],
                [
                    ['title' => 'En que ayudamos', 'text' => 'Configuracion de dispositivos, ayuda con apps, onboarding de cuentas, solucion de problemas y seguimiento.'],
                    ['title' => 'Como generamos confianza', 'text' => 'Los pasos de pago se explican bien, los horarios son visibles y el tono sigue siendo prudente.'],
                ],
                [],
                $this->primaryLinksForLocale('es')
            ),
            'ar' => $this->page(
                'من نحن | Rifi Media للدعم التقني في المغرب',
                'تعرّف على طريقة عمل Rifi Media في إعداد الأجهزة، وبدء الحساب، والمتابعة التقنية الواضحة في مختلف أنحاء المغرب.',
                __('site.nav.about'),
                'Rifi Media شركة مغربية للدعم التقني وإعداد الأجهزة.',
                'طريقة أوضح وأكثر هدوءًا في الإعداد والدعم والمتابعة',
                'تعتمد Rifi Media على خطوات واضحة، ومساعدة عملية، ومتابعة بشرية مناسبة لاحتياجات العملاء في المغرب.',
                [
                    ['title' => 'مسار واضح', 'text' => 'يعرف العميل ما الذي تتم مراجعته وما هي الخطوة التالية دون غموض.'],
                    ['title' => 'دعم بشري', 'text' => 'المتابعة تتم مع أشخاص حقيقيين وبأسلوب واضح وعملي.'],
                    ['title' => 'خدمة على مستوى المغرب', 'text' => 'الخدمة موجهة للعملاء في المغرب مع حضور محلي طبيعي في مراكش.'],
                ],
                [
                    ['title' => 'ما الذي نساعد فيه', 'text' => 'نركّز على إعداد الأجهزة، وتثبيت التطبيقات، والمساعدة في بدء الحساب، وحل المشكلات التقنية.'],
                    ['title' => 'كيف نبني الثقة', 'text' => 'نشرح خطوات الدفع بوضوح، ونُظهر أوقات الدعم، ونستخدم لغة مهنية وآمنة في الصفحات العامة.'],
                ],
                [],
                $this->primaryLinksForLocale('ar')
            ),
        ]);
    }

    protected function contactPage(): array
    {
        $email = config('seo.contact_email', 'contact@rifimedia.com');
        $hours = config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00');
        $whatsapp = config('seo.whatsapp_url', 'https://wa.me/212663323824');

        return $this->localized([
            'en' => $this->page(
                'Contact Rifi Media | Setup help and technical support in Morocco',
                'Talk to Rifi Media about setup help, troubleshooting, onboarding questions, or payment follow-up in Morocco.',
                __('site.nav.support'),
                'Talk to the team about setup, troubleshooting, or billing follow-up.',
                'Start with the clearest support channel for your device or setup need',
                "Contact Rifi Media for Smart TV setup questions, device troubleshooting, app guidance, and practical follow-up support across Morocco. Email: {$email}. Support hours: {$hours}.",
                [
                    ['title' => 'WhatsApp support', 'text' => 'Use WhatsApp for quick setup questions, request review, and practical next steps.'],
                    ['title' => 'Support email', 'text' => "Email {$email} for account questions, billing clarification, and longer-form follow-up."],
                    ['title' => 'Service area', 'text' => 'Support is available across Morocco, with local relevance for Marrakech and nearby clients.'],
                    ['title' => 'Support hours', 'text' => $hours],
                ],
                [
                    ['title' => 'Trust-first contact flow', 'text' => 'Public support is positioned around setup, troubleshooting, onboarding help, and billing clarity only.'],
                    ['title' => 'What to include in your message', 'text' => 'Tell the team which device you use, what stage you reached, and what kind of practical help you need.'],
                ],
                $this->faqItemsForLocale('en'),
                array_merge($this->primaryLinksForLocale('en'), [
                    ['title' => 'Open WhatsApp support', 'text' => 'Start a direct support conversation with the team.', 'url' => $whatsapp],
                ])
            ),
            'fr' => $this->page(
                'Contacter Rifi Media | Aide au setup et support technique au Maroc',
                'Contactez Rifi Media pour le setup, le depannage, l onboarding ou la clarification du paiement au Maroc.',
                __('site.nav.support'),
                'Contactez l equipe pour le setup, le depannage ou le suivi du paiement.',
                'Commencez par le canal de support le plus clair pour votre besoin',
                "Contactez Rifi Media pour le setup Smart TV, l aide applicative, le depannage et le suivi pratique au Maroc. Email : {$email}. Horaires : {$hours}.",
                [
                    ['title' => 'Support WhatsApp', 'text' => 'Utilisez WhatsApp pour les questions rapides, la revue de demande et la prochaine etape.'],
                    ['title' => 'Email de support', 'text' => "Ecrivez a {$email} pour les questions de compte, de paiement et de suivi detaille."],
                    ['title' => 'Zone de service', 'text' => 'Le support couvre le Maroc avec une pertinence naturelle pour Marrakech.'],
                    ['title' => 'Horaires du support', 'text' => $hours],
                ],
                [
                    ['title' => 'Contact de confiance', 'text' => 'Le contact public reste centre sur la configuration, le depannage, l onboarding et la clarte du paiement.'],
                    ['title' => 'Que faut-il indiquer', 'text' => 'Precisez l appareil, l etape atteinte et l aide pratique attendue.'],
                ],
                $this->faqItemsForLocale('fr'),
                array_merge($this->primaryLinksForLocale('fr'), [
                    ['title' => 'Ouvrir WhatsApp', 'text' => 'Demarrer une conversation directe avec le support.', 'url' => $whatsapp],
                ])
            ),
            'es' => $this->page(
                'Contacto Rifi Media | Ayuda de configuracion y soporte tecnico en Marruecos',
                'Habla con Rifi Media sobre configuracion, solucion de problemas, onboarding o seguimiento de pago en Marruecos.',
                __('site.nav.support'),
                'Habla con el equipo sobre configuracion, problemas tecnicos o seguimiento de pago.',
                'Empieza por el canal de soporte mas claro para tu necesidad',
                "Contacta con Rifi Media para dudas sobre Smart TV, apps, solucion de problemas y seguimiento practico en Marruecos. Email: {$email}. Horario: {$hours}.",
                [
                    ['title' => 'Soporte por WhatsApp', 'text' => 'Usa WhatsApp para dudas rapidas, revision del caso y siguientes pasos practicos.'],
                    ['title' => 'Correo de soporte', 'text' => "Escribe a {$email} para preguntas de cuenta, pago o seguimiento mas detallado."],
                    ['title' => 'Cobertura', 'text' => 'El soporte cubre Marruecos con relevancia natural para Marrakech.'],
                    ['title' => 'Horario', 'text' => $hours],
                ],
                [
                    ['title' => 'Contacto con confianza', 'text' => 'El contacto publico se centra solo en configuracion, onboarding, soporte tecnico y claridad de pago.'],
                    ['title' => 'Que incluir en tu mensaje', 'text' => 'Explica el dispositivo, la etapa actual y la ayuda practica que necesitas.'],
                ],
                $this->faqItemsForLocale('es'),
                array_merge($this->primaryLinksForLocale('es'), [
                    ['title' => 'Abrir WhatsApp', 'text' => 'Inicia una conversacion directa con el soporte.', 'url' => $whatsapp],
                ])
            ),
            'ar' => $this->page(
                'تواصل مع Rifi Media | مساعدة الإعداد والدعم التقني في المغرب',
                'تحدث مع Rifi Media بخصوص الإعداد، أو حل المشكلات، أو الاستفسارات الخاصة بالحساب، أو متابعة الدفع في المغرب.',
                __('site.nav.support'),
                'تواصل مع الفريق بخصوص الإعداد، أو المشكلات التقنية، أو متابعة الدفع.',
                'ابدأ عبر قناة الدعم الأنسب لجهازك أو لخطوة الإعداد الحالية',
                "تواصل مع Rifi Media بخصوص إعداد التلفاز الذكي، وتثبيت التطبيقات، وحل المشكلات، والمتابعة العملية في المغرب. البريد: {$email}. أوقات الدعم: {$hours}.",
                [
                    ['title' => 'دعم واتساب', 'text' => 'استخدم واتساب للأسئلة السريعة، ومراجعة الطلب، ومعرفة الخطوة التالية بشكل عملي.'],
                    ['title' => 'بريد الدعم', 'text' => "راسل {$email} إذا كنت تحتاج إلى توضيح يخص الحساب أو الدفع أو المتابعة التفصيلية."],
                    ['title' => 'نطاق الخدمة', 'text' => 'الدعم متاح في مختلف أنحاء المغرب مع حضور محلي طبيعي في مراكش.'],
                    ['title' => 'أوقات الدعم', 'text' => $hours],
                ],
                [
                    ['title' => 'تواصل مبني على الثقة', 'text' => 'التواصل العام يركّز فقط على الإعداد، وحل المشكلات، وبدء الحساب، ووضوح الدفع.'],
                    ['title' => 'ماذا تكتب في رسالتك', 'text' => 'اذكر نوع الجهاز، والمرحلة التي وصلت إليها، وما هي المساعدة العملية التي تحتاجها.'],
                ],
                $this->faqItemsForLocale('ar'),
                array_merge($this->primaryLinksForLocale('ar'), [
                    ['title' => 'فتح واتساب', 'text' => 'ابدأ محادثة مباشرة مع فريق الدعم.', 'url' => $whatsapp],
                ])
            ),
        ]);
    }

    protected function packagesPage(): array
    {
        return $this->localized([
            'en' => [
                'meta_title' => 'Smart TV packs | Rifi Media',
                'meta_description' => 'Compare Smart TV packs for 3, 6, and 12 months with guided setup, app help, and follow-up support in Morocco.',
                'kicker' => 'Smart TV packs',
                'headline' => 'Choose the Smart TV pack that fits your setup and follow-up needs.',
                'description' => 'Each pack is framed around guided setup, app help, practical follow-up, and a clear price in DH.',
            ],
            'fr' => [
                'meta_title' => 'Packs Smart TV | Rifi Media',
                'meta_description' => 'Comparez les packs Smart TV 3 mois, 6 mois et 12 mois avec installation guidee, aide applicative et suivi au Maroc.',
                'kicker' => 'Packs Smart TV',
                'headline' => 'Choisissez le pack Smart TV adapte a votre installation et a votre suivi.',
                'description' => 'Chaque pack est presente autour d une mise en route guidee, d une aide pratique et d un prix clair en DH.',
            ],
            'es' => [
                'meta_title' => 'Packs Smart TV | Rifi Media',
                'meta_description' => 'Compara los packs Smart TV de 3, 6 y 12 meses con instalacion guiada, ayuda con apps y seguimiento en Marruecos.',
                'kicker' => 'Packs Smart TV',
                'headline' => 'Elige el pack Smart TV que mejor encaje con tu configuracion y seguimiento.',
                'description' => 'Cada pack se presenta con instalacion guiada, ayuda practica y un precio claro en DH.',
            ],
            'ar' => [
                'meta_title' => 'خطط الدعم | Rifi Media',
                'meta_description' => 'قارن بين خطط الدعم Basic وAdvanced وPremium لخدمات الإعداد، والمساعدة في التطبيقات، والمتابعة التقنية في المغرب.',
                'kicker' => 'خطط الدعم',
                'headline' => 'اختر خطة الدعم المناسبة لاحتياجك في الإعداد والمتابعة.',
                'description' => 'كل خطة توضّح مستوى المساعدة التقنية، وأولوية الاستجابة، ونطاق الأجهزة، ومدة المتابعة.',
            ],
        ]);
    }

    protected function faqPage(): array
    {
        return $this->localized([
            'en' => [
                'meta_title' => 'FAQ | Rifi Media',
                'meta_description' => 'Answers to common setup, onboarding, payment, and technical support questions from Rifi Media clients in Morocco.',
                'kicker' => 'FAQ',
                'headline' => 'Answers to common setup, onboarding, and technical support questions.',
                'description' => 'Use this page to understand how setup help, payment review, support flow, and follow-up work at Rifi Media.',
            ],
            'fr' => [
                'meta_title' => 'FAQ | Rifi Media',
                'meta_description' => 'Reponses aux questions frequentes sur la configuration, l onboarding, le paiement et le support technique au Maroc.',
                'kicker' => 'FAQ',
                'headline' => 'Reponses aux questions courantes sur la configuration et le support technique.',
                'description' => 'Cette page explique comment fonctionnent l aide au setup, la revue du paiement et le suivi chez Rifi Media.',
            ],
            'es' => [
                'meta_title' => 'FAQ | Rifi Media',
                'meta_description' => 'Respuestas a preguntas frecuentes sobre configuracion, onboarding, pago y soporte tecnico en Marruecos.',
                'kicker' => 'FAQ',
                'headline' => 'Respuestas a preguntas comunes sobre configuracion y soporte tecnico.',
                'description' => 'Esta pagina ayuda a entender el soporte de configuracion, la revision de pago y el seguimiento de Rifi Media.',
            ],
            'ar' => [
                'meta_title' => 'الأسئلة الشائعة | Rifi Media',
                'meta_description' => 'إجابات على الأسئلة الشائعة حول الإعداد، وبدء الحساب، والدفع، والدعم التقني لعملاء Rifi Media في المغرب.',
                'kicker' => 'الأسئلة الشائعة',
                'headline' => 'إجابات على الأسئلة الشائعة حول الإعداد والمتابعة والدعم التقني.',
                'description' => 'تساعدك هذه الصفحة على فهم خطوات الإعداد، ومراجعة الدفع، ومسار الدعم والمتابعة في Rifi Media.',
            ],
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
                ['q' => 'Que propose exactement Rifi Media ?', 'a' => 'Nous aidons pour le setup Smart TV, l installation d applications, le depannage d appareils connectes et le suivi technique pratique.'],
                ['q' => 'Fournissez-vous du contenu media ?', 'a' => 'Non. Nous ne fournissons ni n hebergeons de contenu media. Nous proposons uniquement de l aide a la configuration et au support technique.'],
                ['q' => 'Travaillez-vous dans tout le Maroc ?', 'a' => 'Oui. Le service couvre le Maroc avec une pertinence locale naturelle pour Marrakech.'],
                ['q' => 'Quels paiements acceptez-vous ?', 'a' => 'Les cartes internationales peuvent etre traitees via Paddle, tandis que les virements locaux et les paiements cash sont verifies manuellement.'],
            ],
            'es' => [
                ['q' => 'Que hace exactamente Rifi Media?', 'a' => 'Ayudamos con configuracion de Smart TV, instalacion de apps, solucion de problemas de dispositivos conectados y seguimiento tecnico practico.'],
                ['q' => 'Proporcionan contenido multimedia?', 'a' => 'No. No proporcionamos ni alojamos contenido multimedia. Solo ayudamos con configuracion y soporte tecnico.'],
                ['q' => 'Trabajan en todo Marruecos?', 'a' => 'Si. El servicio cubre Marruecos con relevancia local natural para Marrakech.'],
                ['q' => 'Que metodos de pago aceptan?', 'a' => 'Las tarjetas internacionales pueden gestionarse mediante Paddle, mientras que las transferencias locales y los pagos en efectivo se revisan manualmente.'],
            ],
            'ar' => [
                ['q' => 'ما الذي تقدمه Rifi Media بالضبط؟', 'a' => 'نساعد في إعداد التلفاز الذكي، وتثبيت التطبيقات، وحل مشكلات الأجهزة المتصلة، والمتابعة التقنية العملية.'],
                ['q' => 'هل توفرون محتوى إعلاميًا؟', 'a' => 'لا. نحن لا نوفر ولا نستضيف أي محتوى إعلامي. نحن نقدم فقط المساعدة في إعداد الأجهزة والدعم التقني.'],
                ['q' => 'هل تعملون في جميع أنحاء المغرب؟', 'a' => 'نعم. نطاق الخدمة يشمل المغرب مع حضور محلي طبيعي لطلبات مراكش.'],
                ['q' => 'ما طرق الدفع المتاحة؟', 'a' => 'يمكن معالجة البطاقات الدولية عبر Paddle بينما تتم مراجعة التحويلات المحلية والدفع النقدي يدويًا من طرف الفريق.'],
            ],
            default => [
                ['q' => 'What does Rifi Media actually do?', 'a' => 'We help clients with Smart TV setup, app installation guidance, connected device troubleshooting, and practical technical support.'],
                ['q' => 'Do you provide media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
                ['q' => 'Do you work across Morocco?', 'a' => 'Yes. Our service area covers Morocco, with stronger local relevance for Marrakech.'],
                ['q' => 'What payment options are available?', 'a' => 'Card payments can be handled through Paddle, while local bank transfers and cash payments are reviewed manually.'],
            ],
        };
    }

    protected function servicePages(): array
    {
        return [
            'smart-tv-setup-morocco' => $this->servicePage(
                [
                    'en' => ['meta' => 'Smart TV setup in Morocco | Rifi Media', 'desc' => 'Smart TV setup help in Morocco with practical configuration guidance, app organization, and follow-up support.', 'kicker' => 'Smart TV setup', 'headline' => 'Smart TV setup in Morocco', 'body' => 'Rifi Media helps clients in Morocco configure Smart TVs, organize essential apps, review settings, and reduce first-time setup confusion.'],
                    'fr' => ['meta' => 'Configuration Smart TV au Maroc | Rifi Media', 'desc' => 'Aide a la configuration Smart TV au Maroc avec guidage pratique, organisation des applications et suivi.', 'kicker' => 'Configuration Smart TV', 'headline' => 'Configuration Smart TV au Maroc', 'body' => 'Rifi Media aide les clients au Maroc a configurer leur Smart TV, organiser les applications utiles et simplifier la mise en route.'],
                    'es' => ['meta' => 'Configuracion de Smart TV en Marruecos | Rifi Media', 'desc' => 'Ayuda con configuracion de Smart TV en Marruecos con guia practica, apps y seguimiento.', 'kicker' => 'Configuracion Smart TV', 'headline' => 'Configuracion de Smart TV en Marruecos', 'body' => 'Rifi Media ayuda a clientes en Marruecos a configurar Smart TV, organizar apps utiles y simplificar la puesta en marcha.'],
                    'ar' => ['meta' => 'إعداد التلفاز الذكي في المغرب | Rifi Media', 'desc' => 'مساعدة في إعداد التلفاز الذكي في المغرب مع إرشاد عملي وتنظيم التطبيقات والمتابعة.', 'kicker' => 'إعداد التلفاز الذكي', 'headline' => 'إعداد التلفاز الذكي في المغرب', 'body' => 'تساعد Rifi Media العملاء في المغرب على إعداد التلفاز الذكي، وتنظيم التطبيقات الأساسية، وتقليل الارتباك في البداية.'],
                ],
                [
                    ['en' => 'First-time setup guidance', 'fr' => 'Guidage pour la mise en route', 'es' => 'Guia para la puesta en marcha', 'ar' => 'إرشاد لخطوات البداية'],
                    ['en' => 'Settings and device checks', 'fr' => 'Reglages et verifications', 'es' => 'Ajustes y comprobaciones', 'ar' => 'فحص الإعدادات والتوافق'],
                    ['en' => 'App organization help', 'fr' => 'Organisation des applications', 'es' => 'Ayuda para organizar apps', 'ar' => 'تنظيم التطبيقات المفيدة'],
                    ['en' => 'Clear next-step follow-up', 'fr' => 'Suivi plus clair', 'es' => 'Seguimiento mas claro', 'ar' => 'متابعة واضحة بعد الإعداد'],
                ]
            ),
            'app-installation-help' => $this->servicePage(
                [
                    'en' => ['meta' => 'App installation help in Morocco | Rifi Media', 'desc' => 'Get app installation help in Morocco with clear setup guidance, sign-in help, and practical follow-up.', 'kicker' => 'App guidance', 'headline' => 'App installation help in Morocco', 'body' => 'Rifi Media helps clients install useful apps, complete login steps, organize app placement, and avoid common setup friction.'],
                    'fr' => ['meta' => 'Aide a l installation d applications au Maroc | Rifi Media', 'desc' => 'Aide a l installation d applications au Maroc avec guidage clair, aide a la connexion et suivi.', 'kicker' => 'Aide applicative', 'headline' => 'Aide a l installation d applications au Maroc', 'body' => 'Rifi Media aide les clients a installer des applications utiles, finaliser les connexions et organiser leur appareil plus clairement.'],
                    'es' => ['meta' => 'Ayuda con instalacion de apps en Marruecos | Rifi Media', 'desc' => 'Ayuda con instalacion de apps en Marruecos con guia clara, acceso y seguimiento.', 'kicker' => 'Ayuda con apps', 'headline' => 'Ayuda con instalacion de apps en Marruecos', 'body' => 'Rifi Media ayuda a instalar apps utiles, completar pasos de acceso y organizar mejor el dispositivo.'],
                    'ar' => ['meta' => 'مساعدة تثبيت التطبيقات في المغرب | Rifi Media', 'desc' => 'مساعدة في تثبيت التطبيقات في المغرب مع إرشاد واضح وخطوات دخول ومتابعة عملية.', 'kicker' => 'مساعدة التطبيقات', 'headline' => 'مساعدة تثبيت التطبيقات في المغرب', 'body' => 'تساعد Rifi Media على تثبيت التطبيقات المفيدة، وإكمال خطوات الدخول، وتنظيم الجهاز بطريقة أوضح وأسهل.'],
                ],
                [
                    ['en' => 'Safe setup guidance', 'fr' => 'Guidage de mise en route', 'es' => 'Guia de instalacion', 'ar' => 'إرشاد واضح للتثبيت'],
                    ['en' => 'Login and account steps', 'fr' => 'Etapes de connexion', 'es' => 'Pasos de acceso', 'ar' => 'خطوات الدخول والحساب'],
                    ['en' => 'App organization', 'fr' => 'Organisation des apps', 'es' => 'Organizacion de apps', 'ar' => 'تنظيم التطبيقات'],
                    ['en' => 'Practical follow-up', 'fr' => 'Suivi pratique', 'es' => 'Seguimiento practico', 'ar' => 'متابعة عملية'],
                ]
            ),
            'device-troubleshooting-morocco' => $this->servicePage(
                [
                    'en' => ['meta' => 'Device troubleshooting in Morocco | Rifi Media', 'desc' => 'Device troubleshooting help in Morocco for setup issues, app problems, and practical technical support.', 'kicker' => 'Troubleshooting', 'headline' => 'Device troubleshooting in Morocco', 'body' => 'Rifi Media helps identify setup issues, reduce configuration mistakes, and guide clients through practical fixes for connected devices.'],
                    'fr' => ['meta' => 'Depannage d appareils au Maroc | Rifi Media', 'desc' => 'Aide au depannage d appareils au Maroc pour les problemes de configuration et le support pratique.', 'kicker' => 'Depannage', 'headline' => 'Depannage d appareils au Maroc', 'body' => 'Rifi Media aide a identifier les problemes de configuration, verifier les erreurs et guider les corrections pratiques.'],
                    'es' => ['meta' => 'Solucion de problemas de dispositivos en Marruecos | Rifi Media', 'desc' => 'Ayuda con solucion de problemas en Marruecos para errores de configuracion y soporte tecnico practico.', 'kicker' => 'Solucion de problemas', 'headline' => 'Solucion de problemas de dispositivos en Marruecos', 'body' => 'Rifi Media ayuda a identificar errores de configuracion, revisar problemas tecnicos y guiar correcciones practicas.'],
                    'ar' => ['meta' => 'حل مشكلات الأجهزة في المغرب | Rifi Media', 'desc' => 'مساعدة في حل مشكلات الأجهزة في المغرب بخصوص الإعداد والأخطاء التقنية والمتابعة العملية.', 'kicker' => 'حل المشكلات', 'headline' => 'حل مشكلات الأجهزة في المغرب', 'body' => 'تساعد Rifi Media في تحديد مشاكل الإعداد، وتقليل الأخطاء، وإرشاد العميل إلى حلول عملية للأجهزة المتصلة.'],
                ],
                [
                    ['en' => 'Configuration review', 'fr' => 'Revue de configuration', 'es' => 'Revision de configuracion', 'ar' => 'مراجعة الإعدادات'],
                    ['en' => 'Compatibility checks', 'fr' => 'Verifications de compatibilite', 'es' => 'Comprobaciones de compatibilidad', 'ar' => 'فحص التوافق'],
                    ['en' => 'Practical fixes', 'fr' => 'Corrections pratiques', 'es' => 'Soluciones practicas', 'ar' => 'حلول عملية'],
                    ['en' => 'Support continuity', 'fr' => 'Continuite du support', 'es' => 'Continuidad del soporte', 'ar' => 'استمرار المتابعة'],
                ]
            ),
            'technical-support-morocco' => $this->servicePage(
                [
                    'en' => ['meta' => 'Technical support in Morocco | Rifi Media', 'desc' => 'Technical support in Morocco for setup questions, account onboarding, app guidance, and connected-device issues.', 'kicker' => 'Technical support', 'headline' => 'Technical support in Morocco', 'body' => 'Rifi Media provides structured technical support in Morocco for setup questions, onboarding help, device checks, and practical follow-up.'],
                    'fr' => ['meta' => 'Support technique au Maroc | Rifi Media', 'desc' => 'Support technique au Maroc pour le setup, l onboarding, l aide applicative et le suivi.', 'kicker' => 'Support technique', 'headline' => 'Support technique au Maroc', 'body' => 'Rifi Media propose un support technique structure au Maroc pour les questions de setup, l onboarding, les verifications et le suivi pratique.'],
                    'es' => ['meta' => 'Soporte tecnico en Marruecos | Rifi Media', 'desc' => 'Soporte tecnico en Marruecos para configuracion, onboarding, ayuda con apps y seguimiento.', 'kicker' => 'Soporte tecnico', 'headline' => 'Soporte tecnico en Marruecos', 'body' => 'Rifi Media ofrece soporte tecnico estructurado en Marruecos para configuracion, onboarding, revisiones del dispositivo y seguimiento.'],
                    'ar' => ['meta' => 'الدعم التقني في المغرب | Rifi Media', 'desc' => 'دعم تقني في المغرب لأسئلة الإعداد، وبدء الحساب، والتطبيقات، ومشكلات الأجهزة المتصلة.', 'kicker' => 'الدعم التقني', 'headline' => 'الدعم التقني في المغرب', 'body' => 'تقدم Rifi Media دعمًا تقنيًا منظمًا في المغرب لأسئلة الإعداد، والمساعدة في بدء الحساب، وفحص الأجهزة، والمتابعة العملية.'],
                ],
                [
                    ['en' => 'Setup clarification', 'fr' => 'Clarification du setup', 'es' => 'Aclaracion del setup', 'ar' => 'توضيح خطوات الإعداد'],
                    ['en' => 'Onboarding help', 'fr' => 'Aide a l onboarding', 'es' => 'Ayuda de onboarding', 'ar' => 'المساعدة في بدء الحساب'],
                    ['en' => 'Human-reviewed support', 'fr' => 'Support humain', 'es' => 'Soporte humano', 'ar' => 'دعم بشري'],
                    ['en' => 'Follow-up after setup', 'fr' => 'Suivi apres setup', 'es' => 'Seguimiento despues del setup', 'ar' => 'متابعة بعد الإعداد'],
                ]
            ),
            'account-setup-help-morocco' => $this->servicePage(
                [
                    'en' => ['meta' => 'Account setup help in Morocco | Rifi Media', 'desc' => 'Account setup help in Morocco with onboarding guidance, sign-in support, and practical next-step follow-up.', 'kicker' => 'Account onboarding', 'headline' => 'Account setup help in Morocco', 'body' => 'Rifi Media helps clients move through account onboarding with clearer sign-in steps, practical checks, and calm follow-up.'],
                    'fr' => ['meta' => 'Aide a la mise en route du compte au Maroc | Rifi Media', 'desc' => 'Aide a la mise en route du compte au Maroc avec onboarding, verification et suivi pratique.', 'kicker' => 'Onboarding de compte', 'headline' => 'Aide a la mise en route du compte au Maroc', 'body' => 'Rifi Media aide les clients a avancer dans l onboarding du compte avec des etapes plus claires, des verifications utiles et un suivi pratique.'],
                    'es' => ['meta' => 'Ayuda para configurar cuentas en Marruecos | Rifi Media', 'desc' => 'Ayuda para configurar cuentas en Marruecos con onboarding, accesos y seguimiento practico.', 'kicker' => 'Onboarding de cuenta', 'headline' => 'Ayuda para configurar cuentas en Marruecos', 'body' => 'Rifi Media ayuda a avanzar en el onboarding de cuentas con pasos mas claros, comprobaciones utiles y seguimiento practico.'],
                    'ar' => ['meta' => 'المساعدة في إعداد الحساب في المغرب | Rifi Media', 'desc' => 'مساعدة في إعداد الحساب في المغرب مع خطوات أوضح للدخول، وفحص عملي، ومتابعة هادئة.', 'kicker' => 'بدء الحساب', 'headline' => 'المساعدة في إعداد الحساب في المغرب', 'body' => 'تساعد Rifi Media العملاء على المرور بخطوات بدء الحساب بصورة أوضح، مع فحص عملي ومتابعة تساعد على تقليل الارتباك.'],
                ],
                [
                    ['en' => 'Clear sign-in steps', 'fr' => 'Etapes de connexion claires', 'es' => 'Pasos de acceso claros', 'ar' => 'خطوات دخول أوضح'],
                    ['en' => 'Onboarding checks', 'fr' => 'Verifications utiles', 'es' => 'Comprobaciones utiles', 'ar' => 'فحوصات مفيدة'],
                    ['en' => 'Payment clarification', 'fr' => 'Clarification du paiement', 'es' => 'Claridad de pago', 'ar' => 'توضيح الدفع'],
                    ['en' => 'Practical follow-up', 'fr' => 'Suivi pratique', 'es' => 'Seguimiento practico', 'ar' => 'متابعة عملية'],
                ]
            ),
        ];
    }

    protected function servicePage(array $content, array $cardTitles): array
    {
        $locale = app()->getLocale();
        $page = $content[$locale] ?? $content['en'];

        return $this->page(
            $page['meta'],
            $page['desc'],
            $page['kicker'],
            $page['headline'],
            $page['headline'],
            $page['body'],
            collect($cardTitles)->map(function (array $card) use ($locale) {
                $title = $card[$locale] ?? $card['en'];

                return ['title' => $title, 'text' => $title];
            })->all(),
            [
                [
                    'title' => $locale === 'ar' ? 'نطاق الخدمة' : ($locale === 'fr' ? 'Zone de service' : ($locale === 'es' ? 'Cobertura' : 'Service area')),
                    'text' => $locale === 'ar'
                        ? 'الدعم متاح في المغرب مع حضور محلي طبيعي في مراكش عندما يكون ذلك مناسبًا.'
                        : ($locale === 'fr'
                            ? 'Le support est disponible au Maroc avec une pertinence locale naturelle pour Marrakech.'
                            : ($locale === 'es'
                                ? 'El soporte esta disponible en Marruecos con relevancia local natural para Marrakech.'
                                : 'Support is available across Morocco, with natural local relevance for Marrakech.')),
                ],
                [
                    'title' => $locale === 'ar' ? 'رسالة الثقة' : ($locale === 'fr' ? 'Message de confiance' : ($locale === 'es' ? 'Mensaje de confianza' : 'Trust note')),
                    'text' => $locale === 'ar'
                        ? 'نحن لا نوفر ولا نستضيف أي محتوى إعلامي. نحن نقدم خدمات الإعداد والمساعدة التقنية فقط.'
                        : ($locale === 'fr'
                            ? 'Nous ne fournissons ni n hebergeons de contenu media. Nous proposons uniquement une aide au setup et au support technique.'
                            : ($locale === 'es'
                                ? 'No proporcionamos ni alojamos contenido multimedia. Solo ofrecemos ayuda de configuracion y soporte tecnico.'
                                : 'We do not provide or host media content. We only offer setup guidance and technical support.')),
                ],
            ],
            $this->faqItemsForLocale($locale),
            $this->primaryLinksForLocale($locale),
            'Service'
        );
    }

    protected function primaryLinksForLocale(string $locale): array
    {
        return match ($locale) {
            'fr' => [
                ['title' => 'Services', 'text' => 'Voir tous les services de setup et de support.', 'url' => route('pages.services')],
                ['title' => 'Configuration Smart TV', 'text' => 'Acceder a la page de configuration Smart TV.', 'url' => route('pages.service', 'smart-tv-setup-morocco')],
                ['title' => 'Aide aux applications', 'text' => 'Consulter la page d aide a l installation d applications.', 'url' => route('pages.service', 'app-installation-help')],
                ['title' => 'Support technique', 'text' => 'Revoir la page de support technique au Maroc.', 'url' => route('pages.service', 'technical-support-morocco')],
                ['title' => 'Contact', 'text' => 'Parler avec l equipe avant ou apres la commande.', 'url' => route('pages.contact')],
                ['title' => 'Centre de confiance', 'text' => 'Consulter les politiques et les notes de confiance.', 'url' => route('pages.trust')],
            ],
            'es' => [
                ['title' => 'Servicios', 'text' => 'Ver todos los servicios de configuracion y soporte.', 'url' => route('pages.services')],
                ['title' => 'Configuracion Smart TV', 'text' => 'Ir a la pagina de Smart TV.', 'url' => route('pages.service', 'smart-tv-setup-morocco')],
                ['title' => 'Ayuda con apps', 'text' => 'Ver la pagina de ayuda con apps.', 'url' => route('pages.service', 'app-installation-help')],
                ['title' => 'Soporte tecnico', 'text' => 'Ver la pagina de soporte tecnico en Marruecos.', 'url' => route('pages.service', 'technical-support-morocco')],
                ['title' => 'Contacto', 'text' => 'Habla con el equipo antes o despues del pedido.', 'url' => route('pages.contact')],
                ['title' => 'Centro de confianza', 'text' => 'Consulta politicas y notas de confianza.', 'url' => route('pages.trust')],
            ],
            'ar' => [
                ['title' => 'الخدمات', 'text' => 'راجع جميع خدمات الإعداد والدعم الفني.', 'url' => route('pages.services')],
                ['title' => 'إعداد التلفاز الذكي', 'text' => 'انتقل إلى صفحة إعداد التلفاز الذكي.', 'url' => route('pages.service', 'smart-tv-setup-morocco')],
                ['title' => 'مساعدة التطبيقات', 'text' => 'راجع صفحة المساعدة في تثبيت التطبيقات.', 'url' => route('pages.service', 'app-installation-help')],
                ['title' => 'الدعم التقني', 'text' => 'اطلع على صفحة الدعم التقني في المغرب.', 'url' => route('pages.service', 'technical-support-morocco')],
                ['title' => 'التواصل', 'text' => 'تحدث مع الفريق قبل الطلب أو بعده.', 'url' => route('pages.contact')],
                ['title' => 'مركز الثقة', 'text' => 'راجع السياسات والملاحظات المرتبطة بالثقة والشفافية.', 'url' => route('pages.trust')],
            ],
            default => [
                ['title' => 'Services', 'text' => 'Review all setup and technical support services.', 'url' => route('pages.services')],
                ['title' => 'Smart TV setup', 'text' => 'Read the Smart TV setup page for Morocco.', 'url' => route('pages.service', 'smart-tv-setup-morocco')],
                ['title' => 'App installation help', 'text' => 'Explore the app installation help page.', 'url' => route('pages.service', 'app-installation-help')],
                ['title' => 'Technical support', 'text' => 'Open the technical support page for Morocco.', 'url' => route('pages.service', 'technical-support-morocco')],
                ['title' => 'Contact', 'text' => 'Talk to the team before or after placing an order.', 'url' => route('pages.contact')],
                ['title' => 'Trust center', 'text' => 'Review legal, privacy, refund, and trust information.', 'url' => route('pages.trust')],
            ],
        };
    }

    protected function page(
        string $metaTitle,
        string $metaDescription,
        string $kicker,
        string $title,
        string $headline,
        string $description,
        array $cards,
        array $sections = [],
        array $faqs = [],
        array $links = [],
        ?string $schemaType = null
    ): array {
        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'kicker' => $kicker,
            'title' => $title,
            'headline' => $headline,
            'description' => $description,
            'cards' => $cards,
            'sections' => $sections,
            'faqs' => $faqs,
            'links' => $links,
            'schema_type' => $schemaType,
        ];
    }

    protected function localized(array $map): array
    {
        return Arr::get($map, app()->getLocale(), $map['en']);
    }
}
