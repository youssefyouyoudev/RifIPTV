@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@section('content')
    @php
        $featureHighlights = trans('site.home.feature_highlights');
        $payments = trans('site.home.payments');
        $pricing = trans('site.home.pricing');
        $benefits = trans('site.home.benefits.items');
        $miniPoints = array_values(trans('site.home.mini_points'));
        $isArabic = app()->getLocale() === 'ar';
        $paymentLogos = [
            'paddle' => 'images/payment-paddle.jpg',
            'saham' => 'images/payment-saham-bank.webp',
            'cih' => 'images/payment-cih-bank.jpg',
            'boa' => 'images/payment-bank-of-africa.png',
            'chaabi' => 'images/payment-chaabi-bank.png',
            'cashplus' => 'images/payment-cashplus.png',
            'attijari' => 'images/payment-attijariwafa-bank.png',
        ];

        $hero = match (app()->getLocale()) {
            'ar' => [
                'eyebrow' => 'Ø£ÙƒØ«Ø± Ù…Ù† 10,000 Ù‚Ù†Ø§Ø© Ù…Ø´Ù‡ÙˆØ±Ø©',
                'title_top' => 'Ø´Ø§Ù‡Ø¯ Ø§Ù„Ø¹Ø§Ù„Ù… ÙƒÙ„Ù‡',
                'title_bottom' => 'Ù…Ù† Ø´Ø§Ø´Ø© ÙˆØ§Ø­Ø¯Ø©',
                'description' => 'ÙˆØ§Ø¬Ù‡Ø© Ø£Ø¨Ø³Ø· ÙˆØ£ÙƒØ«Ø± ÙØ®Ø§Ù…Ø© ØªØ¹Ø±Ø¶ Ù‚ÙˆØ© RIF Streaming Ù…Ø¹ Ù‚Ù†ÙˆØ§Øª Ù…Ø´Ù‡ÙˆØ±Ø© ÙˆØ±ÙŠØ§Ø¶Ø© ÙˆØ£ÙÙ„Ø§Ù… ÙˆØªØ±ÙÙŠÙ‡ØŒ Ø¨ØªØ¬Ø±Ø¨Ø© Ù…Ø´Ø§Ù‡Ø¯Ø© Ù…Ø±ÙŠØ­Ø© Ø¹Ù„Ù‰ ÙƒÙ„ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©.',
                'device_text' => 'ÙŠØ¹Ù…Ù„ Ø¹Ù„Ù‰ Ø§Ù„ØªÙ„ÙØ§Ø² ÙˆØ§Ù„ØªØ§Ø¨Ù„Øª ÙˆØ§Ù„Ù‡Ø§ØªÙ ÙˆØ§Ù„ÙˆÙŠØ¨',
                'note' => 'ØªØµÙ…ÙŠÙ… Ø£ÙƒØ«Ø± Ù‡Ø¯ÙˆØ¡Ù‹Ø§ ÙˆÙˆØ¶ÙˆØ­Ù‹Ø§ Ù„ÙŠØ¸Ù‡Ø± Ø§Ù„Ø®Ø¯Ù…Ø© Ø¨Ø´ÙƒÙ„ Ø§Ø­ØªØ±Ø§ÙÙŠ ÙÙŠ Ø§Ù„Ø¹Ø±Ø¨ÙŠØ© ÙˆØ¨Ø§Ù‚ÙŠ Ø§Ù„Ù„ØºØ§Øª.',
                'chips' => [
                    ['icon' => 'trophy', 'label' => 'Ø±ÙŠØ§Ø¶Ø©'],
                    ['icon' => 'calendar-range', 'label' => 'Ø£Ø­Ø¯Ø§Ø« Ù…Ø¨Ø§Ø´Ø±Ø©'],
                    ['icon' => 'film', 'label' => 'Ø£ÙÙ„Ø§Ù…'],
                    ['icon' => 'tv', 'label' => 'ØªØ±ÙÙŠÙ‡'],
                ],
                'stats' => ['10,000+ Ù‚Ù†Ø§Ø©', 'Ø¨Ø« 24/7', 'TV / Mobile / Tablet'],
            ],
            'fr' => [
                'eyebrow' => 'Solutions de streaming fiables',
                'title_top' => 'Le monde entier',
                'title_bottom' => 'sur un seul ecran',
                'description' => 'Une hero plus propre et plus sure qui presente RIF Streaming comme un service de configuration, d assistance technique et de mise en place multi-appareils.',
                'device_text' => 'Compatible TV, tablette, mobile et web',
                'note' => 'Une presentation plus douce, plus claire et mieux equilibree sur toutes les langues.',
                'chips' => [
                    ['icon' => 'settings-2', 'label' => 'Configuration'],
                    ['icon' => 'shield-check', 'label' => 'Securite'],
                    ['icon' => 'monitor-smartphone', 'label' => 'Appareils'],
                    ['icon' => 'messages-square', 'label' => 'Support'],
                ],
                'stats' => ['Support guide', 'Assistance 24/7', 'TV / Mobile / Tablette'],
            ],
            'es' => [
                'eyebrow' => 'Soluciones de streaming fiables',
                'title_top' => 'Todo un mundo',
                'title_bottom' => 'en una pantalla',
                'description' => 'Una hero mas limpia y segura que presenta RIF Streaming como servicio de configuracion, soporte tecnico y preparacion multi-dispositivo.',
                'device_text' => 'Funciona en TV, tablet, movil y web',
                'note' => 'Una presentacion mas clara, equilibrada y profesional en todos los idiomas.',
                'chips' => [
                    ['icon' => 'settings-2', 'label' => 'Configuracion'],
                    ['icon' => 'shield-check', 'label' => 'Seguridad'],
                    ['icon' => 'monitor-smartphone', 'label' => 'Dispositivos'],
                    ['icon' => 'messages-square', 'label' => 'Soporte'],
                ],
                'stats' => ['Guias claras', 'Soporte 24/7', 'TV / Movil / Tablet'],
            ],
            default => [
                'eyebrow' => 'Trusted Streaming Solutions',
                'title_top' => 'The whole world',
                'title_bottom' => 'on one screen',
                'description' => 'A cleaner, safer hero that presents RIF Streaming as a technical setup and support service for multi-device digital entertainment environments.',
                'device_text' => 'Works on TV, tablet, phone, and web',
                'note' => 'A softer, more balanced presentation that stays clean across every language and device size.',
                'chips' => [
                    ['icon' => 'settings-2', 'label' => 'Device Setup'],
                    ['icon' => 'shield-check', 'label' => 'Safe Process'],
                    ['icon' => 'monitor-smartphone', 'label' => 'Multi-Device'],
                    ['icon' => 'messages-square', 'label' => 'Support'],
                ],
                'stats' => ['Clear setup steps', '24/7 support', 'TV / Mobile / Tablet'],
            ],
        };

        $planFamilies = match (app()->getLocale()) {
            'ar' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'Ø¨Ø§Ù‚Ø© Ù…Ø±Ù†Ø© ÙˆØ§Ù‚ØªØµØ§Ø¯ÙŠØ© Ù„Ù„Ù…Ø´Ø§Ù‡Ø¯Ø© Ø§Ù„ÙŠÙˆÙ…ÙŠØ© Ø¨Ø¬ÙˆØ¯Ø© Ù…Ù…ØªØ§Ø²Ø©.',
                    'plans' => [
                        ['name' => 'Ø´Ù‡Ø± ÙˆØ§Ø­Ø¯', 'price' => '70', 'badge' => null, 'subtitle' => 'Ø¯Ø®ÙˆÙ„ Ø³Ø±ÙŠØ¹ Ù„Ù„Ø¨Ø¯Ø§ÙŠØ©', 'benefits' => ['Ù‚Ù†ÙˆØ§Øª Ù…Ø¨Ø§Ø´Ø±Ø© Ù…Ø´Ù‡ÙˆØ±Ø©', 'Ø£ÙÙ„Ø§Ù… ÙˆÙ…Ø³Ù„Ø³Ù„Ø§Øª', 'Ø¨Ø« Ù…Ø³ØªÙ‚Ø±', 'Ø¯Ø¹Ù… ÙˆØ§ØªØ³Ø§Ø¨']],
                        ['name' => '3 Ø£Ø´Ù‡Ø±', 'price' => '149', 'badge' => 'Ø§Ù„Ø£ÙƒØ«Ø± Ø·Ù„Ø¨Ù‹Ø§', 'subtitle' => 'Ø£ÙØ¶Ù„ ØªÙˆØ§Ø²Ù† ÙÙŠ Ø§Ù„Ø³Ø¹Ø±', 'benefits' => ['Ù‚Ù†ÙˆØ§Øª Ù…Ø¨Ø§Ø´Ø±Ø© Ù…Ø´Ù‡ÙˆØ±Ø©', 'Ø£ÙÙ„Ø§Ù… ÙˆÙ…Ø³Ù„Ø³Ù„Ø§Øª', 'Ø¬ÙˆØ¯Ø© Full HD Ùˆ 4K', 'Ø¯Ø¹Ù… Ø³Ø±ÙŠØ¹']],
                        ['name' => '6 Ø£Ø´Ù‡Ø±', 'price' => '249', 'badge' => null, 'subtitle' => 'Ø±Ø§Ø­Ø© Ø£ÙƒØ«Ø± ÙˆØªÙˆÙÙŠØ± Ø£ÙØ¶Ù„', 'benefits' => ['Ù…Ø´Ø§Ù‡Ø¯Ø© Ø·ÙˆÙŠÙ„Ø© Ø§Ù„Ù…Ø¯Ù‰', 'Ø±ÙŠØ§Ø¶Ø© ÙˆØªØ±ÙÙŠÙ‡', 'Ø£Ø¬Ù‡Ø²Ø© Ù…ØªØ¹Ø¯Ø¯Ø©', 'Ù…ØªØ§Ø¨Ø¹Ø© Ø§Ù„Ø¯Ø¹Ù…']],
                        ['name' => '12 Ø´Ù‡Ø±Ù‹Ø§', 'price' => '449', 'badge' => 'Ø£ÙØ¶Ù„ Ù‚ÙŠÙ…Ø©', 'subtitle' => 'Ø§Ù„Ø®ÙŠØ§Ø± Ø§Ù„Ø³Ù†ÙˆÙŠ Ø§Ù„Ø§Ù‚ØªØµØ§Ø¯ÙŠ', 'benefits' => ['Ø£ÙØ¶Ù„ Ø³Ø¹Ø± Ø³Ù†ÙˆÙŠ', 'Ø§Ø³ØªÙ‚Ø±Ø§Ø± Ø£Ø¹Ù„Ù‰', 'Ø£ÙˆÙ„ÙˆÙŠØ© Ø£ÙØ¶Ù„', 'Ø·Ù…Ø£Ù†ÙŠÙ†Ø© Ø·ÙˆØ§Ù„ Ø§Ù„Ø³Ù†Ø©']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'Ø¨Ø§Ù‚Ø© Ø£Ù‚ÙˆÙ‰ Ù„Ø¹Ø´Ø§Ù‚ Ø§Ù„Ø¬ÙˆØ¯Ø© Ø§Ù„Ø¹Ø§Ù„ÙŠØ© ÙˆØ§Ù„Ù‚Ù†ÙˆØ§Øª Ø§Ù„Ø±ÙŠØ§Ø¶ÙŠØ© ÙˆØ§Ù„Ù…Ø­ØªÙˆÙ‰ Ø§Ù„Ù…Ù…ÙŠØ².',
                    'plans' => [
                        ['name' => 'Ø´Ù‡Ø± ÙˆØ§Ø­Ø¯', 'price' => '99', 'badge' => null, 'subtitle' => 'Ù†Ø³Ø®Ø© Ù‚ÙˆÙŠØ© Ù„Ù„Ø§Ø³ØªØ®Ø¯Ø§Ù… Ø§Ù„Ù…ÙƒØ«Ù', 'benefits' => ['Ù‚Ù†ÙˆØ§Øª Ø±ÙŠØ§Ø¶ÙŠØ© Ø£Ù‚ÙˆÙ‰', 'Ø£ÙÙ„Ø§Ù… ÙˆØ³Ù„Ø§Ø³Ù„', 'Ø®ÙˆØ§Ø¯Ù… Ù…Ù…ÙŠØ²Ø©', 'Ø¯Ø¹Ù… Ø³Ø±ÙŠØ¹']],
                        ['name' => '3 Ø£Ø´Ù‡Ø±', 'price' => '249', 'badge' => 'Ø§Ù„Ø£ÙƒØ«Ø± Ù…Ø¨ÙŠØ¹Ù‹Ø§', 'subtitle' => 'Ø§Ù„Ø®ÙŠØ§Ø± Ø§Ù„Ù…ÙØ¶Ù„ Ù„Ø¹Ø´Ø§Ù‚ T-REX', 'benefits' => ['Ø±ÙŠØ§Ø¶Ø© ÙˆÙ…Ø­ØªÙˆÙ‰ Ø¨Ø±ÙŠÙ…ÙŠÙˆÙ…', 'Ø¬ÙˆØ¯Ø© Ø¹Ø§Ù„ÙŠØ©', 'Ø¯Ø¹Ù… Ø°Ùˆ Ø£ÙˆÙ„ÙˆÙŠØ©', 'Ù‚ÙŠÙ…Ø© Ù…ØªÙˆØ§Ø²Ù†Ø©']],
                        ['name' => '6 Ø£Ø´Ù‡Ø±', 'price' => '349', 'badge' => null, 'subtitle' => 'Ù‚ÙˆØ© Ø£ÙƒØ«Ø± Ù…Ø¹ ØªÙˆÙÙŠØ± Ø£ÙØ¶Ù„', 'benefits' => ['Ù…Ø´Ø§Ù‡Ø¯Ø© Ù…Ø±ÙŠØ­Ø©', 'Ø®ÙˆØ§Ø¯Ù… Ù…Ø³ØªÙ‚Ø±Ø©', 'Ø£Ø¬Ù‡Ø²Ø© Ù…ØªØ¹Ø¯Ø¯Ø©', 'ØªØ¬Ø¯ÙŠØ¯ Ø£Ø³Ù‡Ù„']],
                        ['name' => '12 Ø´Ù‡Ø±Ù‹Ø§', 'price' => '599', 'badge' => 'Ø§Ù„Ø®ÙŠØ§Ø± Ø§Ù„Ø³Ù†ÙˆÙŠ', 'subtitle' => 'Ø£ÙØ¶Ù„ Ø§Ù„ØªØ²Ø§Ù… Ø·ÙˆÙŠÙ„ Ø§Ù„Ø£Ù…Ø¯', 'benefits' => ['Ø£ÙØ¶Ù„ Ù‚ÙŠÙ…Ø© Ø³Ù†ÙˆÙŠØ©', 'Ø£ÙˆÙ„ÙˆÙŠØ© Ø£Ø¹Ù„Ù‰', 'Ø§Ø³ØªÙ‚Ø±Ø§Ø± Ù‚ÙˆÙŠ', 'Ø¯Ø¹Ù… Ù…Ø³ØªÙ…Ø±']],
                    ],
                ],
            ],
            'fr' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'Une formule souple et economique pour un streaming quotidien propre et stable.',
                    'plans' => [
                        ['name' => '1 mois', 'price' => '70', 'badge' => null, 'subtitle' => 'Acces rapide pour commencer', 'benefits' => ['Chaines live connues', 'Films et series', 'Lecture stable', 'Support WhatsApp']],
                        ['name' => '3 mois', 'price' => '149', 'badge' => 'Populaire', 'subtitle' => 'Le meilleur equilibre prix / duree', 'benefits' => ['Chaines live connues', 'Films et series', 'Qualite Full HD et 4K', 'Support rapide']],
                        ['name' => '6 mois', 'price' => '249', 'badge' => null, 'subtitle' => 'Plus de confort et plus d economie', 'benefits' => ['Acces longue duree', 'Sports et divertissement', 'Multi-appareils', 'Suivi support']],
                        ['name' => '12 mois', 'price' => '449', 'badge' => 'Meilleure valeur', 'subtitle' => 'Le meilleur choix annuel', 'benefits' => ['Meilleur prix annuel', 'Plus de stabilite', 'Priorite plus forte', 'Tranquillite sur l annee']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'Une ligne plus premium pour les clients qui veulent plus de puissance et de sport.',
                    'plans' => [
                        ['name' => '1 mois', 'price' => '99', 'badge' => null, 'subtitle' => 'Version plus puissante pour usage intensif', 'benefits' => ['Sports premium', 'Films et series', 'Serveurs plus forts', 'Support rapide']],
                        ['name' => '3 mois', 'price' => '249', 'badge' => 'Best seller', 'subtitle' => 'Le choix prefere pour T-REX', 'benefits' => ['Sport et contenu premium', 'Haute qualite', 'Support prioritaire', 'Valeur equilibree']],
                        ['name' => '6 mois', 'price' => '349', 'badge' => null, 'subtitle' => 'Plus de puissance avec plus d economie', 'benefits' => ['Confort longue duree', 'Serveurs stables', 'Multi-appareils', 'Renouvellement simple']],
                        ['name' => '12 mois', 'price' => '599', 'badge' => 'Annuel', 'subtitle' => 'Le meilleur engagement long terme', 'benefits' => ['Meilleur prix annuel', 'Priorite haute', 'Stabilite solide', 'Support continu']],
                    ],
                ],
            ],
            'es' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'Una opcion flexible y economica para un streaming diario limpio y estable.',
                    'plans' => [
                        ['name' => '1 mes', 'price' => '70', 'badge' => null, 'subtitle' => 'Acceso rapido para empezar', 'benefits' => ['Canales en vivo conocidos', 'Peliculas y series', 'Reproduccion estable', 'Soporte por WhatsApp']],
                        ['name' => '3 meses', 'price' => '149', 'badge' => 'Popular', 'subtitle' => 'El mejor equilibrio entre precio y tiempo', 'benefits' => ['Canales en vivo conocidos', 'Peliculas y series', 'Calidad Full HD y 4K', 'Soporte rapido']],
                        ['name' => '6 meses', 'price' => '249', 'badge' => null, 'subtitle' => 'Mas comodidad y mejor ahorro', 'benefits' => ['Acceso largo', 'Deportes y entretenimiento', 'Multi-dispositivo', 'Seguimiento del soporte']],
                        ['name' => '12 meses', 'price' => '449', 'badge' => 'Mejor valor', 'subtitle' => 'La mejor opcion anual', 'benefits' => ['Mejor precio anual', 'Mas estabilidad', 'Mayor prioridad', 'Tranquilidad todo el ano']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'Una linea mas premium para clientes que buscan mas potencia, deporte y estabilidad.',
                    'plans' => [
                        ['name' => '1 mes', 'price' => '99', 'badge' => null, 'subtitle' => 'Version mas fuerte para uso intensivo', 'benefits' => ['Deportes premium', 'Peliculas y series', 'Servidores mas potentes', 'Soporte rapido']],
                        ['name' => '3 meses', 'price' => '249', 'badge' => 'Mas vendido', 'subtitle' => 'La opcion favorita de T-REX', 'benefits' => ['Deporte y contenido premium', 'Alta calidad', 'Soporte prioritario', 'Valor equilibrado']],
                        ['name' => '6 meses', 'price' => '349', 'badge' => null, 'subtitle' => 'Mas potencia con mejor ahorro', 'benefits' => ['Comodidad a largo plazo', 'Servidores estables', 'Multi-dispositivo', 'Renovacion sencilla']],
                        ['name' => '12 meses', 'price' => '599', 'badge' => 'Anual', 'subtitle' => 'La mejor opcion a largo plazo', 'benefits' => ['Mejor precio anual', 'Alta prioridad', 'Estabilidad fuerte', 'Soporte continuo']],
                    ],
                ],
            ],
            default => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'A flexible service plan for everyday setup assistance and simple ongoing support.',
                    'plans' => [
                        ['name' => '1 Month', 'price' => '70', 'badge' => null, 'subtitle' => 'Quick start for initial setup', 'benefits' => ['Device configuration help', 'Setup guidance', 'Stable process', 'WhatsApp support']],
                        ['name' => '3 Months', 'price' => '149', 'badge' => 'Popular', 'subtitle' => 'The best balance of value and duration', 'benefits' => ['Device configuration help', 'Account setup support', 'Higher quality assistance', 'Fast response']],
                        ['name' => '6 Months', 'price' => '249', 'badge' => null, 'subtitle' => 'More comfort and better savings', 'benefits' => ['Longer support window', 'Technical follow-up', 'Multi-device help', 'Support tracking']],
                        ['name' => '12 Months', 'price' => '449', 'badge' => 'Best Value', 'subtitle' => 'The smartest yearly option', 'benefits' => ['Best yearly price', 'More stability', 'Higher priority', 'Peace of mind all year']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'A stronger service line for advanced setup cases, power users, and longer technical follow-up.',
                    'plans' => [
                        ['name' => '1 Month', 'price' => '99', 'badge' => null, 'subtitle' => 'Advanced setup help for heavier usage', 'benefits' => ['Advanced support', 'Configuration review', 'Stronger follow-up', 'Fast response']],
                        ['name' => '3 Months', 'price' => '249', 'badge' => 'Best Seller', 'subtitle' => 'The favorite T-REX choice', 'benefits' => ['Advanced setup support', 'Higher quality assistance', 'Priority support', 'Balanced value']],
                        ['name' => '6 Months', 'price' => '349', 'badge' => null, 'subtitle' => 'More power with better savings', 'benefits' => ['Long-term comfort', 'Stable follow-up', 'Multi-device support', 'Easy renewal']],
                        ['name' => '12 Months', 'price' => '599', 'badge' => 'Annual Pick', 'subtitle' => 'The best long-term commitment', 'benefits' => ['Best yearly price', 'Higher priority', 'Strong stability', 'Continuous support']],
                    ],
                ],
            ],
        };
        $initialFamilySlug = $planFamilies[0]['slug'] ?? 'max-ott';
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell hero-shell">
                <div class="hero-stage">
                    <div class="row g-4 g-xl-5 align-items-center hero-grid">
                        <div class="col-xl-5">
                            <div class="hero-copy {{ $isArabic ? 'hero-copy-ar' : '' }}">
                                <span class="hero-kicker mb-3">{{ $hero['eyebrow'] }}</span>
                                <h1 class="hero-title {{ $isArabic ? 'hero-title-ar' : 'hero-title-compact' }} mb-4">
                                    <span class="d-block">{{ $hero['title_top'] }}</span>
                                    <span class="d-block accent">{{ $hero['title_bottom'] }}</span>
                                </h1>
                                <p class="hero-description text-muted-rif mb-4">{{ $hero['description'] }}</p>

                                <div class="hero-points d-flex flex-wrap gap-3 mb-4">
                                    @foreach ($miniPoints as $point)
                                        <span class="hero-point">
                                            <i data-lucide="check-circle-2" class="icon-sm"></i>
                                            <span>{{ $point }}</span>
                                        </span>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                                    <a href="#plans" class="btn-rif-primary">{{ __('site.home.cta') }}</a>
                                    <a href="#support" class="btn-rif-outline">{{ __('site.home.support.whatsapp') }}</a>
                                </div>

                                <div class="row g-3 hero-stats">
                                    @foreach ($hero['stats'] as $stat)
                                        <div class="col-sm-4">
                                            <div class="metric-pill p-3 text-center h-100">
                                                <span class="hero-stat-value">{{ $stat }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-7">
                            <div class="hero-visual-panel">
                                <div class="hero-visual-media">
                                    <img src="{{ asset('images/hero-light.png') }}" alt="RIF Streaming hero illustration" class="hero-showcase-image-light">
                                    <img src="{{ asset('images/hero-dark.png') }}" alt="RIF Streaming hero illustration" class="hero-showcase-image-dark">
                                    <div class="hero-visual-scrim"></div>

                                    <div class="hero-visual-top">
                                        <span class="hero-visual-badge">{{ $hero['eyebrow'] }}</span>
                                        <span class="hero-visual-badge hero-visual-brand">RIF Streaming</span>
                                    </div>

                                    <div class="hero-device-card">
                                        <span class="hero-device-pill">{{ $hero['device_text'] }}</span>
                                        <p class="hero-device-copy mb-0">{{ $hero['note'] }}</p>
                                    </div>
                                </div>

                                <div class="hero-chip-grid mt-3">
                                    @foreach ($hero['chips'] as $chip)
                                        <div class="hero-chip-card">
                                            <span class="chip-icon">
                                                <i data-lucide="{{ $chip['icon'] }}" class="icon-sm"></i>
                                            </span>
                                            <span class="text-body-rif fw-semibold">{{ $chip['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="features" class="row g-4 mt-2">
                    @foreach ($featureHighlights as $feature)
                        <div class="col-md-6 col-xl-3">
                            <article class="surface-card feature-card p-4">
                                <span class="chip-icon mb-3">
                                    <i data-lucide="{{ $feature['icon'] }}" class="icon-sm"></i>
                                </span>
                                <h3 class="h4 text-body-rif mb-3">{{ $feature['title'] }}</h3>
                                <p class="text-soft-rif mb-0">{{ $feature['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell p-4 p-lg-5">
                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="section-kicker mb-3">{{ $payments['kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-3">{{ $payments['title'] }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ $payments['description'] }}</p>
                </div>

                <article class="payment-featured mb-4">
                    <div class="payment-featured-badge">{{ $payments['methods']['paddle']['badge'] }}</div>
                    <div class="payment-featured-inner">
                        <div class="payment-logo-wrap payment-logo-wrap-xl">
                            <img src="{{ asset($paymentLogos['paddle']) }}" alt="{{ $payments['methods']['paddle']['title'] }}" class="img-fluid payment-logo-image payment-logo-image-paddle">
                        </div>
                    </div>
                </article>

                <div class="payment-logo-grid">
                    @foreach (['cih', 'attijari', 'boa', 'chaabi', 'saham', 'cashplus'] as $paymentKey)
                        <article class="payment-logo-card">
                            <div class="payment-logo-wrap">
                                <img src="{{ asset($paymentLogos[$paymentKey]) }}" alt="{{ $payments['methods'][$paymentKey]['title'] }}" class="img-fluid payment-logo-image">
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="plans" class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                <span class="section-kicker mb-3">{{ $pricing['kicker'] }}</span>
                <h2 class="section-title text-body-rif mb-3">{{ $pricing['title'] }}</h2>
                <p class="text-soft-rif fs-5 mb-0">{{ $pricing['description'] }}</p>
            </div>

            <div class="pack-switcher" data-pack-switcher data-pack-default="{{ $initialFamilySlug }}">
                <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Plan packs">
                    @foreach ($planFamilies as $family)
                        <button
                            type="button"
                            class="pack-toggle-btn {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}"
                            data-pack-toggle="{{ $family['slug'] }}"
                        >
                            <span class="pack-toggle-logo-wrap">
                                <img src="{{ $family['logo'] }}" alt="{{ $family['name'] }}" class="pack-toggle-logo">
                            </span>
                            <span class="pack-toggle-copy">
                                <strong>{{ $family['name'] }}</strong>
                                <small>{{ $family['description'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>

                @foreach ($planFamilies as $family)
                    <article
                        class="surface-card family-pricing-shell p-4 p-lg-5 {{ $family['slug'] === 'max-ott' ? 'family-max' : 'family-trex' }} pack-panel {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}"
                        data-pack-panel="{{ $family['slug'] }}"
                    >
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">
                            <div class="family-pricing-brand">
                                <span class="family-pricing-logo-wrap">
                                    <img src="{{ $family['logo'] }}" alt="{{ $family['name'] }}" class="family-pricing-logo">
                                </span>
                                <div>
                                    <div class="text-soft-rif small text-uppercase fw-bold mb-2">{{ $pricing['label'] }}</div>
                                    <h3 class="h2 text-body-rif mb-2">{{ $family['name'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $family['description'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('checkout') }}" class="btn-rif-outline family-pricing-cta">{{ $pricing['cta'] }}</a>
                        </div>

                        <div class="family-pricing-meta mb-4">
                            @foreach (collect($family['plans'])->flatMap(fn ($plan) => $plan['benefits'])->unique()->take(4) as $highlight)
                                <span class="family-pricing-meta-item">
                                    <i data-lucide="sparkles" class="icon-sm"></i>
                                    <span>{{ $highlight }}</span>
                                </span>
                            @endforeach
                        </div>

                        <div class="row g-3">
                            @foreach ($family['plans'] as $plan)
                                @php
                                    $badgeTone = $loop->index === 1 ? 'popular' : ($loop->last ? 'value' : 'default');
                                    $buttonClass = $loop->index === 1 ? 'btn-rif-primary' : 'btn-rif-secondary';
                                @endphp
                                <div class="col-sm-6 col-xl-3">
                                    <article class="family-plan-mini {{ !empty($plan['badge']) ? 'has-badge' : '' }} family-plan-tone-{{ $badgeTone }}">
                                        @if (!empty($plan['badge']))
                                            <span class="family-plan-badge family-plan-badge-{{ $badgeTone }}">{{ $plan['badge'] }}</span>
                                        @endif
                                        <span class="family-plan-label">{{ __('workflow.common.plan') }}</span>
                                        <div class="family-plan-name">{{ $plan['name'] }}</div>
                                        <p class="family-plan-subtitle">{{ $plan['subtitle'] }}</p>
                                        <div class="family-plan-price">{{ $plan['price'] }} <span>MAD</span></div>

                                        <ul class="family-plan-benefits">
                                            @foreach ($plan['benefits'] as $benefit)
                                                <li>
                                                    <span class="family-plan-check">
                                                        <i data-lucide="check" class="icon-sm"></i>
                                                    </span>
                                                    <span>{{ $benefit }}</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <a href="{{ route('checkout') }}" class="{{ $buttonClass }} w-100 mt-auto">{{ $pricing['cta'] }}</a>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="row g-4">
                        @foreach ($benefits as $benefit)
                            <div class="col-md-6">
                                <article class="surface-card benefit-card p-4">
                                    <span class="chip-icon mb-3" style="background: rgba(214,0,58,0.12); color: var(--rif-red);">
                                        <i data-lucide="{{ $benefit['icon'] }}" class="icon-sm"></i>
                                    </span>
                                    <h3 class="h4 text-body-rif mb-3">{{ $benefit['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $benefit['text'] }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5">
                    <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ __('site.home.benefits.kicker') }}</span>
                    <h2 class="section-title narrative-title text-body-rif mb-3">{{ __('site.home.benefits.title') }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ __('site.home.benefits.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="support" class="section-space pb-5">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="support-banner p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="hero-kicker mb-3">{{ __('site.home.support.kicker') }}</span>
                        <h2 class="section-title text-body-rif mb-3">{{ __('site.home.support.title') }}</h2>
                        <p class="text-soft-rif fs-5 mb-0">{{ __('site.home.support.description') }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                            <a href="https://wa.me/212600000000" class="btn-rif-secondary">{{ __('site.home.support.whatsapp') }}</a>
                            <a href="#plans" class="btn-rif-outline">{{ __('site.home.support.plans') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-pack-switcher]').forEach(function (switcher) {
        const buttons = switcher.querySelectorAll('[data-pack-toggle]');
        const panels = switcher.querySelectorAll('[data-pack-panel]');

        const activate = function (slug) {
            buttons.forEach(function (button) {
                button.classList.toggle('is-active', button.getAttribute('data-pack-toggle') === slug);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-pack-panel') === slug);
            });
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                activate(button.getAttribute('data-pack-toggle'));
            });
        });
    });
});
</script>
@endpush

