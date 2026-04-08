@extends('layouts.app')

@section('title', 'Manage support plans | Rifi Media')
@section('meta_description', 'Admin page for enabling, disabling, and editing support plans.')
@section('meta_robots', 'noindex,nofollow')

@php
    $statusMessage = match (session('status')) {
        'support-plan-saved' => 'Support plan saved successfully.',
        'support-plan-updated' => 'Support plan updated successfully.',
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
                    <span class="section-kicker mb-3">Admin operations</span>
                    <h1 class="legal-title text-body-rif mb-3">Manage support plans</h1>
                    <p class="text-soft-rif fs-5 mb-0">Only enabled plans appear on the homepage, packages page, and onboarding flow. Right now you can keep just one live plan or turn on more whenever you are ready.</p>
                </div>
                <div class="col-xl-4">
                    <div class="workflow-meta-grid">
                        <div class="workflow-meta-item">
                            <span>Enabled now</span>
                            <strong>{{ $enabledCount }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>Total plans</span>
                            <strong>{{ $totalCount }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <article class="surface-card p-4 p-lg-5 h-100">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">Add or overwrite a plan</div>
                    <h2 class="h2 text-body-rif mb-3">Create a plan quickly</h2>
                    <p class="text-soft-rif mb-4">If a family and duration already exist, saving here updates that plan instead of creating a duplicate.</p>

                    <form method="POST" action="{{ route('admin.plans.store') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label class="form-label">Family</label>
                            <select name="family_slug" class="form-control @error('family_slug') is-invalid @enderror" required>
                                <option value="sup">Basic / SUP</option>
                                <option value="max">Advanced / MAX</option>
                                <option value="trex">Premium / TREX</option>
                            </select>
                            @error('family_slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Duration (months)</label>
                            <input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror" min="1" max="36" value="{{ old('duration_months', 12) }}" required>
                            @error('duration_months')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Plan title</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="12 Months">
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Price (MAD)</label>
                            <input type="number" step="0.01" name="price_mad" class="form-control @error('price_mad') is-invalid @enderror" value="{{ old('price_mad') }}" required>
                            @error('price_mad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Badge</label>
                            <input type="text" name="badge_text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text') }}" placeholder="Popular">
                            @error('badge_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0" max="9999">
                            @error('sort_order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Features</label>
                            <textarea name="features_text" class="form-control @error('features_text') is-invalid @enderror" rows="6" placeholder="One feature per line">{{ old('features_text') }}</textarea>
                            @error('features_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-grid gap-2">
                            <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                <span>
                                    <strong class="d-block text-body-rif">Show on website</strong>
                                    <span class="text-soft-rif small">Enabled plans appear everywhere clients can select a plan.</span>
                                </span>
                                <span>
                                    <input type="hidden" name="is_enabled" value="0">
                                    <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', '1') ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                <span>
                                    <strong class="d-block text-body-rif">Feature this plan</strong>
                                    <span class="text-soft-rif small">Use the highlighted card treatment for this duration.</span>
                                </span>
                                <span>
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <button type="submit" class="btn-rif-primary w-100">Save support plan</button>
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
                                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">Plan family</div>
                                    <h2 class="h2 text-body-rif mb-1">{{ $familyLabel }}</h2>
                                    <p class="text-soft-rif mb-0">{{ $familyPlans->where('is_enabled', true)->count() }} live plan(s) in this family.</p>
                                </div>
                                <span class="status-badge {{ $familyPlans->where('is_enabled', true)->count() ? 'status-success' : 'status-warning' }}">
                                    {{ $familyPlans->where('is_enabled', true)->count() ? 'Live on storefront' : 'Hidden from storefront' }}
                                </span>
                            </div>

                            @if ($familyPlans->isEmpty())
                                <div class="soft-card p-4 text-soft-rif">No plans saved for this family yet.</div>
                            @else
                                <div class="d-grid gap-3">
                                    @foreach ($familyPlans as $plan)
                                        <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="soft-card p-4">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="family_slug" value="{{ $plan->family_slug }}">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Duration</label>
                                                    <input type="number" name="duration_months" class="form-control" value="{{ $plan->duration_months }}" min="1" max="36" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $plan->name }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Price (MAD)</label>
                                                    <input type="number" step="0.01" name="price_mad" class="form-control" value="{{ (float) $plan->price_mad }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Badge</label>
                                                    <input type="text" name="badge_text" class="form-control" value="{{ $plan->badge_text }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Sort order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $plan->sort_order ?? 0 }}" min="0" max="9999">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="workflow-meta-grid w-100">
                                                        <div class="workflow-meta-item">
                                                            <span>Plan code</span>
                                                            <strong>{{ strtoupper($plan->family_slug) }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Features</label>
                                                    <textarea name="features_text" class="form-control" rows="4">{{ implode(PHP_EOL, $plan->features ?? []) }}</textarea>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="d-grid gap-2">
                                                        <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                                            <span>
                                                                <strong class="d-block text-body-rif">Show this plan to clients</strong>
                                                                <span class="text-soft-rif small">This affects the homepage, packages page, and onboarding flow.</span>
                                                            </span>
                                                            <span>
                                                                <input type="hidden" name="is_enabled" value="0">
                                                                <input type="checkbox" name="is_enabled" value="1" {{ $plan->is_enabled ? 'checked' : '' }}>
                                                            </span>
                                                        </label>
                                                        <label class="soft-card p-3 d-flex align-items-center justify-content-between gap-3">
                                                            <span>
                                                                <strong class="d-block text-body-rif">Feature this duration</strong>
                                                                <span class="text-soft-rif small">Use the highlighted styling on the public plan card.</span>
                                                            </span>
                                                            <span>
                                                                <input type="hidden" name="is_featured" value="0">
                                                                <input type="checkbox" name="is_featured" value="1" {{ $plan->is_featured ? 'checked' : '' }}>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex align-items-end">
                                                    <button type="submit" class="btn-rif-secondary w-100">Update plan</button>
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
