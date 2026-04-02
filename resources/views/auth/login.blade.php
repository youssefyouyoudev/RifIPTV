@section('title', __('portal.auth.login.title'))
@section('meta_description', __('portal.auth.login.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    <div class="mb-4">
        <span class="section-kicker mb-3">{{ __('portal.auth.login.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.login.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.login.description') }}</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label form-label-rif">{{ __('portal.auth.login.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control form-control-rif">
            @error('email')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                <label for="password" class="form-label form-label-rif mb-0">{{ __('portal.auth.login.password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="nav-link-rif">{{ __('portal.auth.login.forgot') }}</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="form-control form-control-rif">
            @error('password')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-check-rif mb-4">
            <div class="form-check mb-0">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label text-soft-rif" for="remember">{{ __('portal.auth.login.remember') }}</label>
            </div>
        </div>

        <button type="submit" class="btn-rif-secondary w-100">{{ __('portal.auth.login.submit') }}</button>
    </form>

    <p class="text-center text-soft-rif mt-4 mb-0">
        {{ __('portal.auth.login.switch') }}
        <a href="{{ route('register') }}" class="nav-link-rif d-inline">{{ __('portal.auth.login.switch_cta') }}</a>
    </p>
</x-guest-layout>
