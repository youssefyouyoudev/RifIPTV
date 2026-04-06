<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function services(): View
    {
        return view('pages.show', $this->pageData('services'));
    }

    public function about(): View
    {
        return view('pages.show', $this->pageData('about'));
    }

    public function contact(): View
    {
        return view('pages.show', $this->pageData('contact'));
    }

    protected function pageData(string $page): array
    {
        $locale = app()->getLocale();

        $pages = [
            'services' => match ($locale) {
                'fr' => [
                    'title' => 'Services de configuration et support technique',
                    'meta_description' => 'Services professionnels de configuration d appareils, assistance applicative, optimisation et accompagnement technique.',
                    'kicker' => 'Services',
                    'headline' => 'Des services clairs pour configurer, stabiliser et accompagner vos appareils.',
                    'description' => 'Nous aidons les clients a mettre en route leurs ecrans, appareils mobiles et applications utiles avec un support humain et des etapes claires.',
                    'cards' => [
                        ['title' => 'Configuration d appareil', 'text' => 'Installation initiale, verifications de base et mise en route propre.'],
                        ['title' => 'Aide applicative', 'text' => 'Assistance pour l installation, l organisation et les premiers acces.'],
                        ['title' => 'Optimisation', 'text' => 'Ajustements pratiques pour un usage plus stable et plus simple.'],
                        ['title' => 'Support technique', 'text' => 'WhatsApp, tableau de bord et suivi humain pour chaque etape.'],
                    ],
                ],
                'es' => [
                    'title' => 'Servicios de configuracion y soporte tecnico',
                    'meta_description' => 'Servicios profesionales de configuracion de dispositivos, guia de aplicaciones, optimizacion y soporte tecnico continuo.',
                    'kicker' => 'Servicios',
                    'headline' => 'Servicios claros para configurar, estabilizar y acompanar tus dispositivos.',
                    'description' => 'Ayudamos a los clientes a preparar pantallas, moviles y aplicaciones utiles con soporte humano y pasos faciles de seguir.',
                    'cards' => [
                        ['title' => 'Configuracion de dispositivos', 'text' => 'Instalacion inicial, revisiones basicas y puesta en marcha limpia.'],
                        ['title' => 'Guia de aplicaciones', 'text' => 'Ayuda para instalar, organizar y completar los primeros accesos.'],
                        ['title' => 'Optimizacion', 'text' => 'Ajustes practicos para una experiencia mas estable y sencilla.'],
                        ['title' => 'Soporte tecnico', 'text' => 'WhatsApp, panel de control y seguimiento humano en cada paso.'],
                    ],
                ],
                'ar' => [
                    'title' => 'خدمات الإعداد والدعم التقني',
                    'meta_description' => 'خدمات احترافية لإعداد الأجهزة، المساعدة في التطبيقات، التحسين، والمتابعة التقنية الواضحة.',
                    'kicker' => 'الخدمات',
                    'headline' => 'خدمات واضحة لإعداد الأجهزة وتحسينها ومرافقة العميل خطوة بخطوة.',
                    'description' => 'نساعد العملاء في تجهيز الشاشات والأجهزة المحمولة والتطبيقات المفيدة مع دعم بشري وخطوات سهلة الفهم.',
                    'cards' => [
                        ['title' => 'إعداد الأجهزة', 'text' => 'تهيئة أولية، فحص أساسي، وبداية مرتبة وواضحة.'],
                        ['title' => 'المساعدة في التطبيقات', 'text' => 'دعم عملي في التثبيت والتنظيم وإتمام خطوات البداية.'],
                        ['title' => 'التحسين والمتابعة', 'text' => 'تعديلات عملية لتجربة أكثر استقرارا وسهولة.'],
                        ['title' => 'الدعم التقني', 'text' => 'واتساب ولوحة العميل ومتابعة بشرية في كل مرحلة.'],
                    ],
                ],
                default => [
                    'title' => 'Device Setup Services, App Guidance and Technical Support',
                    'meta_description' => 'Professional device setup services, app guidance, troubleshooting, optimization, and technical support for connected devices, smart screens, and client follow-up.',
                    'kicker' => 'Services',
                    'headline' => 'Clear services for setup, optimization, and reliable technical follow-up.',
                    'description' => 'We help clients configure screens, mobile devices, and useful apps with practical guidance and human support.',
                    'cards' => [
                        ['title' => 'Device setup', 'text' => 'Initial configuration, essential checks, and a cleaner first start.'],
                        ['title' => 'App guidance', 'text' => 'Hands-on help for installation, organization, and first-time access.'],
                        ['title' => 'Optimization', 'text' => 'Practical adjustments for a steadier and simpler experience.'],
                        ['title' => 'Technical support', 'text' => 'WhatsApp, dashboard follow-up, and clear human support at each step.'],
                    ],
                ],
            },
            'about' => match ($locale) {
                'fr' => [
                    'title' => 'A propos de RIF Media',
                    'meta_description' => 'Decouvrez l approche RIF Media pour la configuration d appareils, l accompagnement client et le support technique.',
                    'kicker' => 'A propos',
                    'headline' => 'Une equipe qui privilegie la clarte, la confiance et le support humain.',
                    'description' => 'RIF Media est concu comme un service pratique de configuration et d assistance, avec un langage clair, une presentation propre et un suivi responsable.',
                    'cards' => [
                        ['title' => 'Approche claire', 'text' => 'Des etapes simples, des statuts lisibles et un cadre rassurant pour chaque client.'],
                        ['title' => 'Suivi humain', 'text' => 'Chaque demande peut etre prise en charge par un responsable clairement identifie.'],
                        ['title' => 'Operation responsable', 'text' => 'Le site met l accent sur le support, la verification et la clarte des paiements.'],
                    ],
                ],
                'es' => [
                    'title' => 'Sobre RIF Media',
                    'meta_description' => 'Conoce el enfoque de RIF Media para configuracion de dispositivos, acompanamiento al cliente y soporte tecnico.',
                    'kicker' => 'Nosotros',
                    'headline' => 'Un equipo centrado en claridad, confianza y soporte humano.',
                    'description' => 'RIF Media esta pensado como un servicio practico de configuracion y ayuda tecnica, con lenguaje claro, presentacion limpia y seguimiento responsable.',
                    'cards' => [
                        ['title' => 'Enfoque claro', 'text' => 'Pasos simples, estados visibles y una experiencia tranquila para el cliente.'],
                        ['title' => 'Seguimiento humano', 'text' => 'Cada solicitud puede ser atendida por una persona claramente identificada.'],
                        ['title' => 'Operacion responsable', 'text' => 'El sitio prioriza soporte, verificacion y claridad en los pagos.'],
                    ],
                ],
                'ar' => [
                    'title' => 'من نحن - RIF Media',
                    'meta_description' => 'تعرف على طريقة عمل RIF Media في إعداد الأجهزة، مرافقة العملاء، والدعم التقني الواضح.',
                    'kicker' => 'من نحن',
                    'headline' => 'فريق يركز على الوضوح والثقة والدعم البشري الحقيقي.',
                    'description' => 'تم تصميم RIF Media كخدمة عملية للإعداد والمساعدة التقنية، بلغة واضحة وواجهة نظيفة ومتابعة مسؤولة.',
                    'cards' => [
                        ['title' => 'نهج واضح', 'text' => 'خطوات بسيطة، حالات مفهومة، وتجربة مريحة للعميل.'],
                        ['title' => 'متابعة بشرية', 'text' => 'كل طلب يمكن أن يتابعه مسؤول واضح ومعروف.'],
                        ['title' => 'تشغيل مسؤول', 'text' => 'الموقع يركز على الدعم والتحقق ووضوح العمليات المالية.'],
                    ],
                ],
                default => [
                    'title' => 'About RIF Media and Our Support Approach',
                    'meta_description' => 'Learn how RIF Media approaches device configuration, client guidance, payment follow-up, and technical support with clarity and trust.',
                    'kicker' => 'About',
                    'headline' => 'A team focused on clarity, trust, and practical human support.',
                    'description' => 'RIF Media is designed as a practical setup and support service with cleaner language, a calmer interface, and responsible client follow-up.',
                    'cards' => [
                        ['title' => 'Clear process', 'text' => 'Simple steps, visible statuses, and a calmer experience for every client.'],
                        ['title' => 'Human follow-up', 'text' => 'Each request can be handled by a clearly assigned support contact.'],
                        ['title' => 'Responsible operations', 'text' => 'The site emphasizes support, verification, and payment clarity.'],
                    ],
                ],
            },
            'contact' => match ($locale) {
                'fr' => [
                    'title' => 'Contact et support',
                    'meta_description' => 'Contactez RIF Media pour la configuration, l aide technique, le suivi de paiement et l accompagnement client.',
                    'kicker' => 'Contact',
                    'headline' => 'Parlez a l equipe si vous avez besoin d aide avant ou apres votre commande.',
                    'description' => 'Nous pouvons vous aider a choisir le bon service, comprendre le processus de paiement et recevoir un accompagnement technique adapte.',
                    'cards' => [
                        ['title' => 'WhatsApp', 'text' => 'Le moyen le plus rapide pour echanger avec un membre de l equipe.'],
                        ['title' => 'Email', 'text' => 'Utilisez contact@rifimedia.com pour les questions generales et administratives.'],
                        ['title' => 'Centre juridique', 'text' => 'Consultez les politiques si vous voulez verifier les regles et la confidentialite.'],
                    ],
                ],
                'es' => [
                    'title' => 'Contacto y soporte',
                    'meta_description' => 'Contacta con RIF Media para configuracion, ayuda tecnica, seguimiento de pagos y soporte al cliente.',
                    'kicker' => 'Contacto',
                    'headline' => 'Habla con el equipo si necesitas ayuda antes o despues de tu pedido.',
                    'description' => 'Podemos ayudarte a elegir el servicio adecuado, entender el proceso de pago y recibir soporte tecnico claro.',
                    'cards' => [
                        ['title' => 'WhatsApp', 'text' => 'La forma mas rapida de hablar con un miembro del equipo.'],
                        ['title' => 'Correo', 'text' => 'Usa contact@rifimedia.com para preguntas generales y administrativas.'],
                        ['title' => 'Centro legal', 'text' => 'Consulta las politicas si quieres revisar reglas, privacidad y seguridad.'],
                    ],
                ],
                'ar' => [
                    'title' => 'التواصل والدعم',
                    'meta_description' => 'تواصل مع RIF Media للمساعدة في الإعداد، المتابعة التقنية، وخطوات الدفع والدعم.',
                    'kicker' => 'التواصل',
                    'headline' => 'تحدث مع الفريق إذا كنت تحتاج مساعدة قبل الطلب أو بعده.',
                    'description' => 'يمكننا مساعدتك في اختيار الخدمة المناسبة وفهم خطوات الدفع والحصول على متابعة تقنية واضحة.',
                    'cards' => [
                        ['title' => 'واتساب', 'text' => 'أسرع طريقة للتواصل مع أحد أعضاء الفريق.'],
                        ['title' => 'البريد الإلكتروني', 'text' => 'استخدم contact@rifimedia.com للأسئلة العامة والإدارية.'],
                        ['title' => 'المركز القانوني', 'text' => 'راجع السياسات إذا أردت فهم القواعد والخصوصية والأمان.'],
                    ],
                ],
                default => [
                    'title' => 'Contact RIF Media for Setup and Support',
                    'meta_description' => 'Contact RIF Media for setup guidance, technical support, payment follow-up, WhatsApp assistance, and client support.',
                    'kicker' => 'Contact',
                    'headline' => 'Talk to the team if you need help before or after your order.',
                    'description' => 'We can help you choose the right service, understand payment steps, and get clearer technical support.',
                    'cards' => [
                        ['title' => 'WhatsApp', 'text' => 'The fastest way to speak with a member of the team.'],
                        ['title' => 'Email', 'text' => 'Use contact@rifimedia.com for general and administrative questions.'],
                        ['title' => 'Trust center', 'text' => 'Review our policies if you want details about privacy, security, and service rules.'],
                    ],
                ],
            },
        ];

        return [
            'page' => $pages[$page],
            'pageKey' => $page,
        ];
    }
}
