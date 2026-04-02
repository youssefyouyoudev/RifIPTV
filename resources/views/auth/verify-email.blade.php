@section('title', __('portal.auth.verify.title'))
@section('meta_description', __('portal.auth.verify.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    <div class="mb-4">
        <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ __('portal.auth.verify.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.verify.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.verify.description') }}</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success rounded-4">{{ __('portal.auth.verify.sent') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-sm-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-rif-secondary w-100">{{ __('portal.auth.verify.submit') }}</button>
            </form>
        </div>
        <div class="col-sm-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ghost-btn w-100 justify-content-center">{{ __('portal.auth.verify.logout') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>
