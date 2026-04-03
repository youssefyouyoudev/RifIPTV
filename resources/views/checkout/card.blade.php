@extends('layouts.app')

@section('title', __('workflow.checkout.title'))
@section('meta_description', __('workflow.checkout.meta_description'))
@section('meta_robots', 'noindex,nofollow')

@section('content')
<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <article class="surface-card p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <span class="section-kicker mb-3">{{ __('workflow.checkout.kicker') }}</span>
                        <h1 class="legal-title text-body-rif mb-3">{{ __('workflow.checkout.headline') }}</h1>
                        <p class="text-soft-rif mb-0">{{ __('workflow.checkout.description') }}</p>
                    </div>

                    <div class="checkout-brand-panel mb-4">
                        <img src="{{ asset('images/payment-paddle.jpg') }}" alt="Paddle" class="checkout-brand-logo">
                        <span class="status-badge status-success">{{ __('workflow.checkout.badge') }}</span>
                    </div>

                    <div class="workflow-meta-grid mb-4">
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.plan') }}</span><strong>{{ $subscription->plan->name }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.amount') }}</span><strong>{{ number_format((float) $transaction->amount_mad, 2) }} {{ __('workflow.common.currency') }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.provider') }}</span><strong>{{ $transaction->provider ?: 'Paddle' }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.reference') }}</span><strong>{{ $transaction->reference }}</strong></div>
                    </div>

                    <div class="soft-card p-4 mb-4">
                        <div class="fw-semibold text-body-rif mb-2">{{ __('workflow.checkout.note_title') }}</div>
                        <p class="text-soft-rif mb-0">{{ __('workflow.checkout.note_text') }}</p>
                    </div>

                    <form method="POST" action="{{ route('checkout.card.confirm') }}" class="d-grid gap-3">
                        @csrf
                        <button type="submit" class="btn-rif-secondary w-100">{{ __('workflow.checkout.confirm') }}</button>
                        <a href="{{ route('checkout') }}" class="btn-rif-outline w-100">{{ __('workflow.checkout.back') }}</a>
                    </form>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
