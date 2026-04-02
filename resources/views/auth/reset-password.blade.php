@section('title', __('portal.auth.reset.title'))
@section('meta_description', __('portal.auth.reset.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    <div class="mb-4">
        <span class="section-kicker mb-3" style="background: rgba(214,0,58,0.10); color: var(--rif-red);">{{ __('portal.auth.reset.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.reset.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.reset.description') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label form-label-rif">{{ __('portal.auth.reset.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus class="form-control form-control-rif">
            @error('email')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label form-label-rif">{{ __('portal.auth.reset.password') }}</label>
            <input id="password" name="password" type="password" required class="form-control form-control-rif">
            @error('password')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label form-label-rif">{{ __('portal.auth.reset.password_confirmation') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control form-control-rif">
        </div>

        <button type="submit" class="btn-rif-secondary w-100">{{ __('portal.auth.reset.submit') }}</button>
    </form>
</x-guest-layout>
