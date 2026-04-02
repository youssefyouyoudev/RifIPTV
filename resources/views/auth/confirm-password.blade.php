@section('title', __('portal.auth.confirm.title'))
@section('meta_description', __('portal.auth.confirm.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    <div class="mb-4">
        <span class="section-kicker mb-3">{{ __('portal.auth.confirm.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.confirm.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.confirm.description') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label for="password" class="form-label form-label-rif">{{ __('portal.auth.confirm.password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="form-control form-control-rif">
            @error('password')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-rif-secondary w-100">{{ __('portal.auth.confirm.submit') }}</button>
    </form>
</x-guest-layout>
