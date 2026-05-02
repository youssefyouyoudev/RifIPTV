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
            'smart_tv' => 'smart_tv',
            'sup', 'basic' => 'basic',
            'max', 'advanced' => 'advanced',
            'trex', 'premium' => 'premium',
            default => $slug,
        };
    }

    protected static function codeFor(string $slug): string
    {
        return match ($slug) {
            'smart_tv' => 'STV',
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
            'ar' => ':months شهرا',
            default => ':months Months',
        };
    }

    protected static function defaultFeaturedBadge(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Meilleure valeur',
            'es' => 'Mejor valor',
            'ar' => 'أفضل قيمة',
            default => 'Best Value',
        };
    }

    protected static function defaultChooseCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Commander maintenant',
            'es' => 'Pedir ahora',
            'ar' => 'اطلب الآن',
            default => 'Order now',
        };
    }

    protected static function defaultContinueCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Commander maintenant',
            'es' => 'Continuar pedido',
            'ar' => 'متابعة الطلب',
            default => 'Continue order',
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
                'smart_tv' => [
                    'label' => 'Smart TV',
                    'name' => 'Packs Smart TV',
                    'summary' => 'Choisissez 3, 6 ou 12 mois pour votre Smart TV avec installation guidee et suivi pratique.',
                    'scope' => 'Setup Smart TV',
                    'devices' => '1 ecran principal',
                    'response' => 'Standard',
                    'follow_up' => 'Suivi guide',
                    'features' => [
                        'Configuration Smart TV etape par etape',
                        'Aide a l installation des applications',
                        'Verification du compte et de l acces',
                        'Suivi pratique via WhatsApp',
                    ],
                    'featured_badge' => 'Meilleure valeur',
                    'choose_cta' => 'Commander maintenant',
                    'continue_cta' => 'Commander maintenant',
                    'talk_cta' => 'Parler au support',
                ],
                'basic' => [
                    'label' => 'Support',
                    'name' => 'Pack SUP',
                    'summary' => 'La formule SUP reste disponible en 12 mois pour un support clair et stable.',
                    'scope' => 'Support SUP',
                    'devices' => '1 appareil principal',
                    'response' => 'Standard',
                    'follow_up' => 'Suivi essentiel',
                    'features' => [
                        'Aide de configuration pas a pas',
                        'Guidage pratique pour les applications',
                        'Verification du compte et de l acces',
                        'Support de clarification via WhatsApp',
                    ],
                    'featured_badge' => 'En ligne',
                    'choose_cta' => 'Commander maintenant',
                    'continue_cta' => 'Commander maintenant',
                    'talk_cta' => 'Parler au support',
                ],
                'advanced' => [
                    'label' => 'Avance',
                    'name' => 'Support avance',
                    'summary' => 'Pour un perimetre plus large, une priorite plus forte et un suivi plus long.',
                    'highlight' => 'Le plus choisi',
                    'scope' => 'Configuration etendue',
                    'devices' => 'Jusqu a 2 appareils',
                    'response' => 'Prioritaire',
                    'follow_up' => 'Suivi etendu',
                    'features' => [
                        'Guidage de configuration plus complet',
                        'Aide a l organisation des applications',
                        'Verifications techniques prioritaires',
                        'Fenetre de suivi prolongee',
                    ],
                    'featured_badge' => 'Recommande',
                ],
                'premium' => [
                    'label' => 'Premium',
                    'name' => 'Support premium',
                    'summary' => 'Pour un onboarding plus pousse, une priorite plus elevee et une continuite plus forte.',
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
                'smart_tv' => [
                    'label' => 'Smart TV',
                    'name' => 'Packs Smart TV',
                    'summary' => 'Elige 3, 6 o 12 meses para Smart TV con instalacion guiada y seguimiento practico.',
                    'scope' => 'Setup Smart TV',
                    'devices' => '1 pantalla principal',
                    'response' => 'Estandar',
                    'follow_up' => 'Seguimiento guiado',
                    'features' => [
                        'Configuracion Smart TV paso a paso',
                        'Ayuda con instalacion de aplicaciones',
                        'Revision de cuenta y acceso',
                        'Seguimiento practico por WhatsApp',
                    ],
                    'featured_badge' => 'Mejor valor',
                    'choose_cta' => 'Pedir ahora',
                    'continue_cta' => 'Pedir ahora',
                    'talk_cta' => 'Hablar con soporte',
                ],
                'basic' => [
                    'label' => 'Support',
                    'name' => 'Pack SUP',
                    'summary' => 'La formula SUP sigue disponible en 12 meses para soporte claro y estable.',
                    'scope' => 'Support SUP',
                    'devices' => '1 dispositivo principal',
                    'response' => 'Estandar',
                    'follow_up' => 'Seguimiento esencial',
                    'features' => [
                        'Ayuda de configuracion paso a paso',
                        'Guia practica para aplicaciones',
                        'Revision de cuenta y acceso',
                        'Soporte de aclaracion por WhatsApp',
                    ],
                    'featured_badge' => 'Disponible',
                    'choose_cta' => 'Pedir ahora',
                    'continue_cta' => 'Pedir ahora',
                    'talk_cta' => 'Hablar con soporte',
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
                    'devices' => 'Ayuda multidispositivo',
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
                'smart_tv' => [
                    'label' => 'Smart TV',
                    'name' => 'باقات Smart TV',
                    'summary' => 'اختر 3 أو 6 أو 12 شهرا لخدمة Smart TV مع إعداد موجه ومتابعة عملية.',
                    'scope' => 'إعداد Smart TV',
                    'devices' => 'شاشة رئيسية واحدة',
                    'response' => 'عادي',
                    'follow_up' => 'متابعة موجهة',
                    'features' => [
                        'إعداد Smart TV خطوة بخطوة',
                        'مساعدة في تثبيت التطبيقات',
                        'مراجعة الحساب والوصول',
                        'متابعة عملية عبر واتساب',
                    ],
                    'featured_badge' => 'أفضل قيمة',
                    'choose_cta' => 'اطلب الآن',
                    'continue_cta' => 'متابعة الطلب',
                    'talk_cta' => 'تحدث مع الدعم',
                ],
                'basic' => [
                    'label' => 'الدعم',
                    'name' => 'باقة SUP',
                    'summary' => 'تبقى باقة SUP متاحة لمدة 12 شهرا للدعم الواضح والمستقر.',
                    'scope' => 'دعم SUP',
                    'devices' => 'جهاز رئيسي واحد',
                    'response' => 'عادي',
                    'follow_up' => 'متابعة أساسية',
                    'features' => [
                        'مساعدة في الإعداد خطوة بخطوة',
                        'إرشاد عملي للتطبيقات',
                        'مراجعة الحساب والوصول',
                        'دعم توضيحي عبر واتساب',
                    ],
                    'featured_badge' => 'متاح',
                    'choose_cta' => 'اطلب الآن',
                    'continue_cta' => 'متابعة الطلب',
                    'talk_cta' => 'تحدث مع الدعم',
                ],
                'advanced' => [
                    'label' => 'متقدم',
                    'name' => 'الدعم المتقدم',
                    'summary' => 'لنطاق إعداد أوسع، وأولوية أعلى، وفترة متابعة أطول عبر أكثر من جهاز.',
                    'highlight' => 'الأكثر اختيارا',
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
                    'featured_badge' => 'الأكثر مبيعا',
                ],
            ],
        ];
    }
}
