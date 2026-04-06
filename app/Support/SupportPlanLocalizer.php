<?php

namespace App\Support;

class SupportPlanLocalizer
{
    public static function localize(array $plans, string $locale): array
    {
        $translations = self::translations()[$locale] ?? [];

        return array_map(function (array $plan) use ($translations, $locale): array {
            $slug = $plan['slug'] ?? '';
            $localized = $translations[$slug] ?? [];
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
                $price['duration_label'] = str_replace(':months', (string) $price['months'], $monthsLabel);

                return $price;
            }, $plan['prices'] ?? []);

            return $plan;
        }, $plans);
    }

    protected static function codeFor(string $slug): string
    {
        return match ($slug) {
            'basic' => 'SUP',
            'advanced' => 'MAX',
            'premium' => 'TREX',
            default => strtoupper($slug),
        };
    }

    protected static function monthsLabel(string $locale): string
    {
        return match ($locale) {
            'fr' => ':months mois',
            'es' => ':months meses',
            'ar' => ':months أشهر',
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
                    'summary' => 'Pour les demandes plus simples, l installation guidee et un premier suivi rassurant.',
                    'scope' => 'Configuration de base',
                    'devices' => '1 appareil',
                    'response' => 'Standard',
                    'follow_up' => 'Suivi essentiel',
                    'features' => [
                        'Aide pas a pas pour la configuration',
                        'Guide d installation des applications',
                        'Revue du compte et de l acces',
                        'Clarifications WhatsApp',
                    ],
                    'featured_badge' => 'Populaire',
                ],
                'advanced' => [
                    'label' => 'Avance',
                    'name' => 'Support avance',
                    'summary' => 'Pour une configuration plus large, une priorite renforcee et un suivi plus long.',
                    'highlight' => 'Le plus choisi',
                    'scope' => 'Configuration etendue',
                    'devices' => 'Jusqu a 2 appareils',
                    'response' => 'Prioritaire',
                    'follow_up' => 'Suivi etendu',
                    'features' => [
                        'Configuration plus complete',
                        'Organisation pratique des applications',
                        'Verifications techniques prioritaires',
                        'Fenetre de suivi plus longue',
                    ],
                    'featured_badge' => 'Recommande',
                ],
                'premium' => [
                    'label' => 'Premium',
                    'name' => 'Support premium',
                    'summary' => 'Pour un onboarding plus pousse, un traitement plus prioritaire et une meilleure continuite.',
                    'highlight' => 'Meilleure valeur',
                    'scope' => 'Accompagnement complet',
                    'devices' => 'Multi-appareils',
                    'response' => 'Priorite haute',
                    'follow_up' => 'Suivi longue duree',
                    'features' => [
                        'Onboarding plus approfondi',
                        'Traitement plus prioritaire',
                        'Revue technique avancee',
                        'Continuite de support renforcee',
                    ],
                    'featured_badge' => 'Best seller',
                ],
            ],
            'es' => [
                'basic' => [
                    'label' => 'Basico',
                    'name' => 'Soporte basico',
                    'summary' => 'Para necesidades simples de configuracion, instalacion guiada y seguimiento inicial.',
                    'scope' => 'Configuracion base',
                    'devices' => '1 dispositivo',
                    'response' => 'Estándar',
                    'follow_up' => 'Seguimiento esencial',
                    'features' => [
                        'Ayuda paso a paso para configurar el equipo',
                        'Guia de instalacion de aplicaciones',
                        'Revision de cuenta y acceso',
                        'Soporte de aclaracion por WhatsApp',
                    ],
                    'featured_badge' => 'Popular',
                ],
                'advanced' => [
                    'label' => 'Avanzado',
                    'name' => 'Soporte avanzado',
                    'summary' => 'Para un alcance mas amplio, mayor prioridad y seguimiento mas largo.',
                    'highlight' => 'Mas elegido',
                    'scope' => 'Configuracion ampliada',
                    'devices' => 'Hasta 2 dispositivos',
                    'response' => 'Prioritario',
                    'follow_up' => 'Seguimiento extendido',
                    'features' => [
                        'Guia de configuracion mas amplia',
                        'Ayuda para organizar aplicaciones',
                        'Revisiones tecnicas prioritarias',
                        'Ventana de seguimiento mas larga',
                    ],
                    'featured_badge' => 'Recomendado',
                ],
                'premium' => [
                    'label' => 'Premium',
                    'name' => 'Soporte premium',
                    'summary' => 'Para onboarding mas profundo, respuesta prioritaria y continuidad tecnica mas fuerte.',
                    'highlight' => 'Mejor valor',
                    'scope' => 'Soporte completo',
                    'devices' => 'Ayuda multi-dispositivo',
                    'response' => 'Prioridad alta',
                    'follow_up' => 'Seguimiento largo',
                    'features' => [
                        'Asistencia de onboarding mas profunda',
                        'Respuesta de mayor prioridad',
                        'Revision avanzada de problemas',
                        'Continuidad de soporte mas larga',
                    ],
                    'featured_badge' => 'Mas vendido',
                ],
            ],
            'ar' => [
                'basic' => [
                    'label' => 'أساسي',
                    'name' => 'الدعم الأساسي',
                    'summary' => 'للإعدادات الأبسط، وخطوات التثبيت الموجهة، والمتابعة الأولى المريحة.',
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
                    'scope' => 'إعداد موسع',
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
                    'summary' => 'لبدء أكثر عمقًا، وأولوية أعلى، واستمرارية تقنية أقوى على المدى الطويل.',
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
