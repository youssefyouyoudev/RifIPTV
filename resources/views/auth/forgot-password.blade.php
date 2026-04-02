@section('title', __('portal.auth.forgot.title'))
@section('meta_description', __('portal.auth.forgot.meta_description'))
@section('meta_robots', 'noindex,nofollow')

<x-guest-layout>
    <div class="mb-4">
        <span class="section-kicker mb-3" style="background: rgba(255,213,0,0.15); color: #c28f00;">{{ __('portal.auth.forgot.kicker') }}</span>
        <h1 class="h2 text-body-rif mb-3">{{ __('portal.auth.forgot.headline') }}</h1>
        <p class="text-soft-rif mb-0">{{ __('portal.auth.forgot.description') }}</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label form-label-rif">{{ __('portal.auth.forgot.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="form-control form-control-rif">
            @error('email')
                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-rif-secondary w-100">{{ __('portal.auth.forgot.submit') }}</button>
    </form>
</x-guest-layout>
