@section('title', __('portal.auth.register.title'))
@section('meta_description', __('portal.auth.register.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    @php
        $phoneCodes = trans('portal.phone_codes');
    @endphp

    <div class="mb-4">
        <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ __('portal.auth.register.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.register.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.register.description') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label form-label-rif">{{ __('portal.auth.register.name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control form-control-rif">
            @error('name')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label form-label-rif">{{ __('portal.auth.register.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="form-control form-control-rif">
            @error('email')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-5">
                <label for="phone_country_code" class="form-label form-label-rif">{{ __('portal.auth.register.phone_country_code') }}</label>
                <select id="phone_country_code" name="phone_country_code" required class="form-select form-control-rif">
                    @foreach ($phoneCodes as $entry)
                        <option value="{{ $entry['code'] }}" {{ old('phone_country_code', '+212') === $entry['code'] ? 'selected' : '' }}>{{ $entry['label'] }} ({{ $entry['code'] }})</option>
                    @endforeach
                </select>
                @error('phone_country_code')
                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-sm-7">
                <label for="phone_number" class="form-label form-label-rif">{{ __('portal.auth.register.phone_number') }}</label>
                <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number') }}" required inputmode="tel" autocomplete="tel-national" class="form-control form-control-rif" placeholder="612345678">
                @error('phone_number')
                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-12">
                <p class="text-soft-rif small mb-0">{{ __('portal.auth.register.phone_hint') }}</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-sm-6">
                <label for="password" class="form-label form-label-rif">{{ __('portal.auth.register.password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-control form-control-rif">
                @error('password')
                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-sm-6">
                <label for="password_confirmation" class="form-label form-label-rif">{{ __('portal.auth.register.password_confirmation') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="form-control form-control-rif">
            </div>
        </div>

        <div class="soft-card p-3 mt-4 text-soft-rif">{{ __('portal.auth.register.note') }}</div>

        <button type="submit" class="btn-rif-secondary w-100 mt-4">{{ __('portal.auth.register.submit') }}</button>
    </form>

    <p class="text-center text-soft-rif mt-4 mb-0">
        {{ __('portal.auth.register.switch') }}
        <a href="{{ route('login') }}" class="nav-link-rif d-inline">{{ __('portal.auth.register.switch_cta') }}</a>
    </p>
</x-guest-layout>
