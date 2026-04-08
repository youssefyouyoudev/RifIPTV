<?php

namespace App\Support;

class SupportPlanLocalizer
{
    public static function localize(array $plans, string $locale): array
    {
        $translations = self::translations()[$locale] ?? [];

        return array_map(function (array $plan) use ($translations, $locale): array {
            $slug = (string) ($plan['slug'] ?? '');
            $translationKey = self::translationKey($slug);
            $localized = $translations[$translationKey] ?? [];
            $monthsLabel = self::monthsLabel($locale);

            $plan = array_merge($plan, array_diff_key($localized, ['features' => true]));

            if (isset($localized['features'])) {
                $plan['features'] = $localized['features'];
            }

            $plan['code'] = self::codeFor($slug);
            $plan['featured_badge'] = $localized['featured_badge'] ?? self::defaultFeaturedBadge($locale);
            $plan['choose_cta'] = $localized['choose_cta'] ?? self::defaultChooseCta($locale);
            $plan['continue_cta'] = $localized['continue_cta'] ?? self::defaultContinueCta($locale);
            $plan['talk_cta'] = $localized['talk_cta'] ?? self::defaultTalkCta($locale);

            $plan['prices'] = array_map(function (array $price) use ($monthsLabel): array {
                $price['duration_label'] = str_replace(':months', (string) ($price['months'] ?? 0), $monthsLabel);

                return $price;
            }, $plan['prices'] ?? []);

            return $plan;
        }, $plans);
    }

    public static function codeForFamily(string $slug): string
    {
        return self::codeFor($slug);
    }

    public static function durationLabel(int $months, string $locale): string
    {
        return str_replace(':months', (string) $months, self::monthsLabel($locale));
    }

    public static function familyContent(string $slug, string $locale): array
    {
        $translations = self::translations()[$locale] ?? [];
        $translationKey = self::translationKey($slug);

        return $translations[$translationKey] ?? [];
    }

    protected static function translationKey(string $slug): string
    {
        return match ($slug) {
            'sup', 'basic' => 'basic',
            'max', 'advanced' => 'advanced',
            'trex', 'premium' => 'premium',
            default => $slug,
        };
    }

    protected static function codeFor(string $slug): string
    {
        return match ($slug) {
            'basic', 'sup' => 'SUP',
            'advanced', 'max' => 'MAX',
            'premium', 'trex' => 'TREX',
            default => strtoupper($slug),
        };
    }

    protected static function monthsLabel(string $locale): string
    {
        return match ($locale) {
            'fr' => ':months mois',
            'es' => ':months meses',
            'ar' => ':months شهرًا',
            default => ':months Months',
        };
    }

    protected static function defaultFeaturedBadge(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Populaire',
            'es' => 'Popular',
            'ar' => 'الأكثر طلبًا',
            default => 'Popular',
        };
    }

    protected static function defaultChooseCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Choisir',
            'es' => 'Elegir',
            'ar' => 'اختيار',
            default => 'Choose',
        };
    }

    protected static function defaultContinueCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Continuer',
            'es' => 'Continuar',
            'ar' => 'متابعة',
            default => 'Continue',
        };
    }

    protected static function defaultTalkCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Parler au support',
            'es' => 'Hablar con soporte',
            'ar' => 'تحدث مع الدعم',
            default => 'Talk to support',
        };
    }

    protected static function translations(): array
    {
        return [
            'fr' => [
                'basic' => [
                    'label' => 'Essentiel',
                    'name' => 'Support essentiel',
                    'summary' => 'Pour les besoins simples, l’installation guidée et un premier suivi rassurant.',
                    'scope' => 'Configuration de base',
                    'devices' => '1 appareil',
                    'response' => 'Standard',
                    'follow_up' => 'Suivi essentiel',
                    'features' => [
                        'Aide pas à pas pour la configuration',
                        'Guide d’installation des applications',
                        'Revue du compte et de l’accès',
                        'Clarifications par WhatsApp',
                    ],
                    'featured_badge' => 'Populaire',
                ],
                'advanced' => [
                    'label' => 'Avancé',
                    'name' => 'Support avancé',
                    'summary' => 'Pour un périmètre plus large, une priorité plus forte et un suivi plus long.',
                    'highlight' => 'Le plus choisi',
                    'scope' => 'Configuration étendue',
                    'devices' => 'Jusqu’à 2 appareils',
                    'response' => 'Prioritaire',
                    'follow_up' => 'Suivi étendu',
                    'features' => [
                        'Guidage de configuration plus complet',
                        'Aide à l’organisation des applications',
                        'Vérifications techniques prioritaires',
                        'Fenêtre de suivi prolongée',
                    ],
                    'featured_badge' => 'Recommandé',
                ],
                'premium' => [
                    'label' => 'Premium',
                    'name' => 'Support premium',
                    'summary' => 'Pour un onboarding plus poussé, une priorité plus élevée et une continuité plus forte.',
                    'highlight' => 'Meilleure valeur',
                    'scope' => 'Accompagnement complet',
                    'devices' => 'Multi-appareils',
                    'response' => 'Priorité haute',
                    'follow_up' => 'Suivi longue durée',
                    'features' => [
                        'Onboarding plus approfondi',
                        'Traitement plus prioritaire',
                        'Revue technique avancée',
                        'Continuité de support renforcée',
                    ],
                    'featured_badge' => 'Best seller',
                ],
            ],
            'es' => [
                'basic' => [
                    'label' => 'Básico',
                    'name' => 'Soporte básico',
                    'summary' => 'Para necesidades simples de configuración, instalación guiada y seguimiento inicial.',
                    'scope' => 'Configuración base',
                    'devices' => '1 dispositivo',
                    'response' => 'Estándar',
                    'follow_up' => 'Seguimiento esencial',
                    'features' => [
                        'Ayuda paso a paso para configurar el equipo',
                        'Guía de instalación de aplicaciones',
                        'Revisión de cuenta y acceso',
                        'Aclaraciones por WhatsApp',
                    ],
                    'featured_badge' => 'Popular',
                ],
                'advanced' => [
                    'label' => 'Avanzado',
                    'name' => 'Soporte avanzado',
                    'summary' => 'Para un alcance más amplio, mayor prioridad y seguimiento más largo.',
                    'highlight' => 'Más elegido',
                    'scope' => 'Configuración ampliada',
                    'devices' => 'Hasta 2 dispositivos',
                    'response' => 'Prioritario',
                    'follow_up' => 'Seguimiento extendido',
                    'features' => [
                        'Guía de configuración más amplia',
                        'Ayuda para organizar aplicaciones',
                        'Revisiones técnicas prioritarias',
                        'Ventana de seguimiento más larga',
                    ],
                    'featured_badge' => 'Recomendado',
                ],
                'premium' => [
                    'label' => 'Premium',
                    'name' => 'Soporte premium',
                    'summary' => 'Para onboarding más profundo, respuesta prioritaria y continuidad técnica más fuerte.',
                    'highlight' => 'Mejor valor',
                    'scope' => 'Soporte completo',
                    'devices' => 'Ayuda multidispositivo',
                    'response' => 'Prioridad alta',
                    'follow_up' => 'Seguimiento largo',
                    'features' => [
                        'Asistencia de onboarding más profunda',
                        'Respuesta de mayor prioridad',
                        'Revisión avanzada de problemas',
                        'Continuidad de soporte más larga',
                    ],
                    'featured_badge' => 'Más vendido',
                ],
            ],
            'ar' => [
                'basic' => [
                    'label' => 'أساسي',
                    'name' => 'الدعم الأساسي',
                    'summary' => 'للاحتياجات الأبسط، وخطوات التثبيت الموجهة، والمتابعة الأولى المريحة.',
                    'scope' => 'إعداد أساسي',
                    'devices' => 'جهاز واحد',
                    'response' => 'عادي',
                    'follow_up' => 'متابعة أساسية',
                    'features' => [
                        'مساعدة خطوة بخطوة في إعداد الجهاز',
                        'إرشاد في تثبيت التطبيقات',
                        'مراجعة الحساب والوصول',
                        'توضيح سريع عبر واتساب',
                    ],
                    'featured_badge' => 'الأكثر طلبًا',
                ],
                'advanced' => [
                    'label' => 'متقدم',
                    'name' => 'الدعم المتقدم',
                    'summary' => 'لنطاق إعداد أوسع، وأولوية أعلى، وفترة متابعة أطول عبر أكثر من جهاز.',
                    'highlight' => 'الأكثر اختيارًا',
                    'scope' => 'إعداد موسّع',
                    'devices' => 'حتى جهازين',
                    'response' => 'أولوية',
                    'follow_up' => 'متابعة ممتدة',
                    'features' => [
                        'إرشاد إعداد أشمل',
                        'مساعدة في تنظيم التطبيقات',
                        'فحوصات تقنية ذات أولوية',
                        'نافذة متابعة أطول',
                    ],
                    'featured_badge' => 'موصى به',
                ],
                'premium' => [
                    'label' => 'بريميوم',
                    'name' => 'الدعم البريميوم',
                    'summary' => 'لبدء أعمق، وأولوية أعلى، واستمرارية تقنية أقوى على المدى الطويل.',
                    'highlight' => 'أفضل قيمة',
                    'scope' => 'دعم كامل',
                    'devices' => 'مساعدة متعددة الأجهزة',
                    'response' => 'أولوية عالية',
                    'follow_up' => 'متابعة طويلة',
                    'features' => [
                        'مساعدة أعمق في بدء الإعداد',
                        'تعامل أسرع مع الطلبات',
                        'مراجعة متقدمة للمشكلات التقنية',
                        'استمرارية دعم أطول',
                    ],
                    'featured_badge' => 'الأكثر مبيعًا',
                ],
            ],
        ];
    }
}
