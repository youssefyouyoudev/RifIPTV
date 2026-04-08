<?php

namespace App\Support;

use App\Models\Plan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SupportPlanCatalog
{
    public static function forStorefront(string $locale): Collection
    {
        if (Schema::hasTable('plans')) {
            $plans = Plan::query()
                ->where('is_enabled', true)
                ->orderBy('sort_order')
                ->orderBy('duration_months')
                ->get();

            if ($plans->isNotEmpty()) {
                return $plans
                    ->groupBy(fn (Plan $plan) => (string) $plan->family_slug)
                    ->map(fn (Collection $familyPlans, string $familySlug) => self::buildFamily($familySlug, $familyPlans, $locale))
                    ->values();
            }
        }

        return collect(SupportPlanLocalizer::localize(config('support_plans.plans', []), $locale));
    }

    protected static function buildFamily(string $familySlug, Collection $familyPlans, string $locale): array
    {
        $firstPlan = $familyPlans->sortBy('duration_months')->first();
        $localized = SupportPlanLocalizer::familyContent($familySlug, $locale);
        $code = SupportPlanLocalizer::codeForFamily($familySlug);

        return [
            'slug' => $familySlug,
            'code' => $code,
            'label' => $localized['label'] ?? $code,
            'name' => $localized['name'] ?? ($firstPlan?->family ?? $code),
            'summary' => $localized['summary'] ?? '',
            'highlight' => $localized['highlight'] ?? null,
            'scope' => $localized['scope'] ?? null,
            'devices' => $localized['devices'] ?? null,
            'response' => $localized['response'] ?? null,
            'follow_up' => $localized['follow_up'] ?? null,
            'features' => $localized['features'] ?? (($firstPlan?->features && is_array($firstPlan->features)) ? $firstPlan->features : []),
            'featured_badge' => $localized['featured_badge'] ?? '',
            'choose_cta' => $localized['choose_cta'] ?? self::chooseCta($locale),
            'continue_cta' => $localized['continue_cta'] ?? self::continueCta($locale),
            'talk_cta' => $localized['talk_cta'] ?? self::talkCta($locale),
            'prices' => $familyPlans
                ->sortBy('duration_months')
                ->map(fn (Plan $plan) => [
                    'id' => $plan->id,
                    'months' => (int) $plan->duration_months,
                    'price' => (int) round((float) $plan->price_mad),
                    'featured' => (bool) $plan->is_featured,
                    'duration_label' => SupportPlanLocalizer::durationLabel((int) $plan->duration_months, $locale),
                    'badge' => $plan->badge_text,
                ])
                ->values()
                ->all(),
        ];
    }

    protected static function chooseCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Choisir',
            'es' => 'Elegir',
            'ar' => 'اختيار',
            default => 'Choose',
        };
    }

    protected static function continueCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Continuer',
            'es' => 'Continuar',
            'ar' => 'متابعة',
            default => 'Continue',
        };
    }

    protected static function talkCta(string $locale): string
    {
        return match ($locale) {
            'fr' => 'Parler au support',
            'es' => 'Hablar con soporte',
            'ar' => 'تحدث مع الدعم',
            default => 'Talk to support',
        };
    }
}
