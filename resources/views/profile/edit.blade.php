@extends('layouts.app')

@section('title', __('portal.profile.title'))
@section('meta_description', __('portal.profile.meta_description'))
@section('meta_robots', 'noindex,nofollow')

@section('content')
<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <span class="section-kicker mb-3">{{ __('portal.profile.kicker') }}</span>
            <h1 class="legal-title text-body-rif mb-3">{{ __('portal.profile.headline') }}</h1>
            <p class="text-soft-rif fs-5 mb-0">{{ __('portal.profile.description') }}</p>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <article class="surface-card p-4 p-lg-5">
                    <div class="mb-4">
                        <h2 class="form-section-title text-body-rif mb-2">{{ __('portal.profile.sections.profile.title') }}</h2>
                        <p class="text-soft-rif mb-0">{{ __('portal.profile.sections.profile.description') }}</p>
                    </div>

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success rounded-4 border-0 mb-4">{{ __('portal.profile.sections.profile.saved') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        @php
                            $phoneCodes = trans('portal.phone_codes');
                        @endphp
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label form-label-rif">{{ __('portal.profile.fields.name') }}</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-control form-control-rif">
                                @error('name')
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label form-label-rif">{{ __('portal.profile.fields.email') }}</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-control form-control-rif">
                                @error('email')
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="phone_country_code" class="form-label form-label-rif">{{ __('portal.profile.fields.phone_country_code') }}</label>
                                <select id="phone_country_code" name="phone_country_code" class="form-select form-control-rif">
                                    @foreach ($phoneCodes as $entry)
                                        <option value="{{ $entry['code'] }}" @selected(old('phone_country_code', $user->phone_country_code ?: '+212') === $entry['code'])>{{ $entry['label'] }} ({{ $entry['code'] }})</option>
                                    @endforeach
                                </select>
                                @error('phone_country_code')
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="phone_number" class="form-label form-label-rif">{{ __('portal.profile.fields.phone_number') }}</label>
                                <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel-national" class="form-control form-control-rif">
                                @error('phone_number')
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-rif-secondary">{{ __('portal.profile.sections.profile.submit') }}</button>
                        </div>
                    </form>
                </article>
            </div>

            <div class="col-12">
                <article class="surface-card p-4 p-lg-5">
                    <div class="mb-4">
                        <h2 class="form-section-title text-body-rif mb-2">{{ __('portal.profile.sections.password.title') }}</h2>
                        <p class="text-soft-rif mb-0">{{ __('portal.profile.sections.password.description') }}</p>
                    </div>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success rounded-4 border-0 mb-4">{{ __('portal.profile.sections.password.saved') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <label for="current_password" class="form-label form-label-rif">{{ __('portal.profile.fields.current_password') }}</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="form-control form-control-rif">
                                @if ($errors->updatePassword->has('current_password'))
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $errors->updatePassword->first('current_password') }}</p>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <label for="password" class="form-label form-label-rif">{{ __('portal.profile.fields.password') }}</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" class="form-control form-control-rif">
                                @if ($errors->updatePassword->has('password'))
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $errors->updatePassword->first('password') }}</p>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <label for="password_confirmation" class="form-label form-label-rif">{{ __('portal.profile.fields.password_confirmation') }}</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control form-control-rif">
                                @if ($errors->updatePassword->has('password_confirmation'))
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-rif-secondary">{{ __('portal.profile.sections.password.submit') }}</button>
                        </div>
                    </form>
                </article>
            </div>

            <div class="col-12">
                <article class="surface-card danger-card p-4 p-lg-5">
                    <div class="mb-4">
                        <h2 class="form-section-title text-body-rif mb-2">{{ __('portal.profile.sections.delete.title') }}</h2>
                        <p class="text-soft-rif mb-0">{{ __('portal.profile.sections.delete.description') }}</p>
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="row g-4 align-items-end">
                            <div class="col-lg-8">
                                <label for="delete_password" class="form-label form-label-rif">{{ __('portal.profile.fields.delete_password') }}</label>
                                <input id="delete_password" name="password" type="password" class="form-control form-control-rif" placeholder="{{ __('portal.profile.sections.delete.placeholder') }}">
                                @if ($errors->userDeletion->has('password'))
                                    <p class="small text-rif-danger mt-2 mb-0">{{ $errors->userDeletion->first('password') }}</p>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <button type="submit" class="danger-button w-100">{{ __('portal.profile.sections.delete.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
