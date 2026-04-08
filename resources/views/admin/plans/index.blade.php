@extends('layouts.app')

@section('title', 'Manage support plans | Rifi Media')
@section('meta_description', 'Admin page for enabling, disabling, and editing support plans.')
@section('meta_robots', 'noindex,nofollow')

@php
    $locale = app()->getLocale();
    $copy = match ($locale) {
        'ar' => [
            'title' => 'إدارة خطط الدعم',
            'description' => 'الخطط المفعّلة فقط هي التي تظهر في الصفحة الرئيسية وصفحة الباقات ومسار الطلب. يمكنك الإبقاء على خطة واحدة مباشرة أو تفعيل المزيد لاحقًا.',
            'enabled' => 'المفعّل الآن',
            'total' => 'إجمالي الخطط',
            'add_title' => 'أضف خطة بسرعة',
            'add_text' => 'إذا كانت نفس الفئة ونفس المدة موجودتين بالفعل، فسيتم تحديث الخطة الحالية بدل إنشاء نسخة مكررة.',
            'save' => 'حفظ خطة الدعم',
            'family' => 'الفئة',
            'duration' => 'المدة بالأشهر',
            'plan_title' => 'عنوان الخطة',
            'price' => 'السعر بالدرهم',
            'badge' => 'البادج',
            'sort' => 'ترتيب العرض',
            'features' => 'المزايا',
            'features_placeholder' => 'ميزة واحدة في كل سطر',
            'show' => 'إظهار الخطة في الموقع',
            'show_hint' => 'الخطط المفعّلة تظهر في الصفحة الرئيسية وصفحة الباقات ومسار الطلب.',
            'feature' => 'تمييز هذه الخطة',
            'feature_hint' => 'استخدم التنسيق البارز لهذه المدة في البطاقة العامة.',
            'update' => 'تحديث الخطة',
            'plan_family' => 'فئة الخطة',
            'plan_code' => 'رمز الخطة',
            'live' => 'مرئية للعامة',
            'hidden' => 'مخفية عن العامة',
            'none' => 'لا توجد خطط محفوظة لهذه الفئة بعد.',
            'saved' => 'تم حفظ خطة الدعم بنجاح.',
            'updated' => 'تم تحديث خطة الدعم بنجاح.',
            'live_count' => ':count خطة مباشرة في هذه الفئة.',
            'name_placeholder' => '12 شهرًا',
            'badge_placeholder' => 'الأكثر طلبًا',
            'enabled_hint' => 'يؤثر هذا مباشرة على الصفحة الرئيسية وصفحة الباقات ومسار الطلب.',
        ],
        'fr' => [
            'title' => 'Gérer les plans de support',
            'description' => 'Seuls les plans actifs apparaissent sur la page d accueil, la page packages et le parcours de commande. Vous pouvez garder un seul plan en ligne ou en activer davantage plus tard.',
            'enabled' => 'Actifs maintenant',
            'total' => 'Total des plans',
            'add_title' => 'Ajouter un plan rapidement',
            'add_text' => 'Si la même famille et la même durée existent déjà, l enregistrement met à jour le plan au lieu de créer un doublon.',
            'save' => 'Enregistrer le plan',
            'family' => 'Famille',
            'duration' => 'Durée en mois',
            'plan_title' => 'Titre du plan',
            'price' => 'Prix en MAD',
            'badge' => 'Badge',
            'sort' => 'Ordre d affichage',
            'features' => 'Fonctionnalités',
            'features_placeholder' => 'Une fonctionnalité par ligne',
            'show' => 'Afficher ce plan sur le site',
            'show_hint' => 'Les plans actifs apparaissent partout où le client peut choisir une offre.',
            'feature' => 'Mettre ce plan en avant',
            'feature_hint' => 'Utiliser le style visuel mis en avant pour cette durée.',
            'update' => 'Mettre à jour',
            'plan_family' => 'Famille du plan',
            'plan_code' => 'Code du plan',
            'live' => 'Visible sur le site',
            'hidden' => 'Caché du site',
            'none' => 'Aucun plan enregistré pour cette famille.',
            'saved' => 'Le plan de support a été enregistré.',
            'updated' => 'Le plan de support a été mis à jour.',
            'live_count' => ':count plan(s) actif(s) dans cette famille.',
            'name_placeholder' => '12 mois',
            'badge_placeholder' => 'Populaire',
            'enabled_hint' => 'Cela affecte la page d accueil, la page packages et le parcours de commande.',
        ],
        default => [
            'title' => 'Manage support plans',
            'description' => 'Only enabled plans appear on the homepage, packages page, and onboarding flow. Right now you can keep just one live plan or turn on more whenever you are ready.',
            'enabled' => 'Enabled now',
            'total' => 'Total plans',
            'add_title' => 'Create a plan quickly',
            'add_text' => 'If a family and duration already exist, saving here updates that plan instead of creating a duplicate.',
            'save' => 'Save support plan',
            'family' => 'Family',
            'duration' => 'Duration (months)',
            'plan_title' => 'Plan title',
            'price' => 'Price (MAD)',
            'badge' => 'Badge',
            'sort' => 'Sort order',
            'features' => 'Features',
            'features_placeholder' => 'One feature per line',
            'show' => 'Show on website',
            'show_hint' => 'Enabled plans appear everywhere clients can select a plan.',
            'feature' => 'Feature this plan',
            'feature_hint' => 'Use the highlighted card treatment for this duration.',
            'update' => 'Update plan',
            'plan_family' => 'Plan family',
            'plan_code' => 'Plan code',
            'live' => 'Live on storefront',
            'hidden' => 'Hidden from storefront',
            'none' => 'No plans saved for this family yet.',
            'saved' => 'Support plan saved successfully.',
            'updated' => 'Support plan updated successfully.',
            'live_count' => ':count live plan(s) in this family.',
            'name_placeholder' => '12 Months',
            'badge_placeholder' => 'Popular',
            'enabled_hint' => 'This affects the homepage, packages page, and onboarding flow.',
        ],
    };
    $statusMessage = match (session('status')) {
        'support-plan-saved' => $copy['saved'],
        'support-plan-updated' => $copy['updated'],
        default => null,
    };
@endphp

@section('content')
<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if ($statusMessage)
            <div class="alert alert-rif-success mb-4">{{ $statusMessage }}</div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-end">
                <div class="col-xl-8">
                    <span class="section-kicker mb-3">{{ __('workflow.admin.eyebrow') }}</span>
                    <h1 class="legal-title text-body-rif mb-3">{{ $copy['title'] }}</h1>
                    <p class="text-soft-rif fs-5 mb-0">{{ $copy['description'] }}</p>
                </div>
                <div class="col-xl-4">
                    <div class="workflow-meta-grid">
                        <div class="workflow-meta-item">
                            <span>{{ $copy['enabled'] }}</span>
                            <strong>{{ $enabledCount }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>{{ $copy['total'] }}</span>
                            <strong>{{ $totalCount }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <article class="surface-card p-4 p-lg-5 h-100">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ $copy['title'] }}</div>
                    <h2 class="h2 text-body-rif mb-3">{{ $copy['add_title'] }}</h2>
                    <p class="text-soft-rif mb-4">{{ $copy['add_text'] }}</p>

                    <form method="POST" action="{{ route('admin.plans.store') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label class="form-label">{{ $copy['family'] }}</label>
                            <select name="family_slug" class="form-control @error('family_slug') is-invalid @enderror" required>
                                <option value="sup">Basic / SUP</option>
                                <option value="max">Advanced / MAX</option>
                                <option value="trex">Premium / TREX</option>
                            </select>
                            @error('family_slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['duration'] }}</label>
                            <input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror" min="1" max="36" value="{{ old('duration_months', 12) }}" required>
                            @error('duration_months')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['plan_title'] }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ $copy['name_placeholder'] }}">
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['price'] }}</label>
                            <input type="number" step="0.01" name="price_mad" class="form-control @error('price_mad') is-invalid @enderror" value="{{ old('price_mad') }}" required>
                            @error('price_mad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['badge'] }}</label>
                            <input type="text" name="badge_text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text') }}" placeholder="{{ $copy['badge_placeholder'] }}">
                            @error('badge_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['sort'] }}</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0" max="9999">
                            @error('sort_order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">{{ $copy['features'] }}</label>
                            <textarea name="features_text" class="form-control @error('features_text') is-invalid @enderror" rows="6" placeholder="{{ $copy['features_placeholder'] }}">{{ old('features_text') }}</textarea>
                            @error('features_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-grid gap-2">
                            <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                <span>
                                    <strong class="d-block text-body-rif">{{ $copy['show'] }}</strong>
                                    <span class="text-soft-rif small">{{ $copy['show_hint'] }}</span>
                                </span>
                                <span>
                                    <input type="hidden" name="is_enabled" value="0">
                                    <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', '1') ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                <span>
                                    <strong class="d-block text-body-rif">{{ $copy['feature'] }}</strong>
                                    <span class="text-soft-rif small">{{ $copy['feature_hint'] }}</span>
                                </span>
                                <span>
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <button type="submit" class="btn-rif-primary w-100">{{ $copy['save'] }}</button>
                    </form>
                </article>
            </div>

            <div class="col-xl-8">
                <div class="d-grid gap-4">
                    @foreach ($familyLabels as $familySlug => $familyLabel)
                        @php($familyPlans = $planGroups->get($familySlug, collect()))
                        <article class="surface-card p-4 p-lg-5">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                                <div>
                                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ $copy['plan_family'] }}</div>
                                    <h2 class="h2 text-body-rif mb-1">{{ $familyLabel }}</h2>
                                    <p class="text-soft-rif mb-0">{{ str_replace(':count', (string) $familyPlans->where('is_enabled', true)->count(), $copy['live_count']) }}</p>
                                </div>
                                <span class="status-badge {{ $familyPlans->where('is_enabled', true)->count() ? 'status-success' : 'status-warning' }}">
                                    {{ $familyPlans->where('is_enabled', true)->count() ? $copy['live'] : $copy['hidden'] }}
                                </span>
                            </div>

                            @if ($familyPlans->isEmpty())
                                <div class="soft-card p-4 text-soft-rif">{{ $copy['none'] }}</div>
                            @else
                                <div class="d-grid gap-3">
                                    @foreach ($familyPlans as $plan)
                                        <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="soft-card p-4">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="family_slug" value="{{ $plan->family_slug }}">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">{{ $copy['duration'] }}</label>
                                                    <input type="number" name="duration_months" class="form-control" value="{{ $plan->duration_months }}" min="1" max="36" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">{{ $copy['plan_title'] }}</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $plan->name }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ $copy['price'] }}</label>
                                                    <input type="number" step="0.01" name="price_mad" class="form-control" value="{{ (float) $plan->price_mad }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ $copy['badge'] }}</label>
                                                    <input type="text" name="badge_text" class="form-control" value="{{ $plan->badge_text }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ $copy['sort'] }}</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $plan->sort_order ?? 0 }}" min="0" max="9999">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="workflow-meta-grid w-100">
                                                        <div class="workflow-meta-item">
                                                            <span>{{ $copy['plan_code'] }}</span>
                                                            <strong>{{ strtoupper($plan->family_slug) }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">{{ $copy['features'] }}</label>
                                                    <textarea name="features_text" class="form-control" rows="4">{{ implode(PHP_EOL, $plan->features ?? []) }}</textarea>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="d-grid gap-2">
                                                        <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                                            <span>
                                                                <strong class="d-block text-body-rif">{{ $copy['show'] }}</strong>
                                                                <span class="text-soft-rif small">{{ $copy['enabled_hint'] }}</span>
                                                            </span>
                                                            <span>
                                                                <input type="hidden" name="is_enabled" value="0">
                                                                <input type="checkbox" name="is_enabled" value="1" {{ $plan->is_enabled ? 'checked' : '' }}>
                                                            </span>
                                                        </label>
                                                        <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                                            <span>
                                                                <strong class="d-block text-body-rif">{{ $copy['feature'] }}</strong>
                                                                <span class="text-soft-rif small">{{ $copy['feature_hint'] }}</span>
                                                            </span>
                                                            <span>
                                                                <input type="hidden" name="is_featured" value="0">
                                                                <input type="checkbox" name="is_featured" value="1" {{ $plan->is_featured ? 'checked' : '' }}>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex align-items-end">
                                                    <button type="submit" class="btn-rif-secondary w-100">{{ $copy['update'] }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
