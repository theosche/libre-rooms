@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<div class="auth-container container-full-form">
    <div class="form-header">
        <h1 class="form-title">{{ __('Register') }}</h1>
    </div>

    @if($errors->any())
        <div class="error-messages">
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="styled-form">
        @csrf

        <div class="form-group">
            <div class="form-element">
                <label for="name" class="form-element-title">{{ __('Name') }}</label>
                <div class="form-field">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-element">
                <label for="email" class="form-element-title">{{ __('Email') }}</label>
                <div class="form-field">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
            </div>

            <div class="form-element">
                <label for="password" class="form-element-title">{{ __('Password') }}</label>
                <div class="form-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                </div>
            </div>

            <div class="form-element">
                <label for="password_confirmation" class="form-element-title">{{ __('Confirm password') }}</label>
                <div class="form-field">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                    >
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">{{ __('Sign up') }}</button>
        </div>
    </form>

    <p class="auth-link">
        {{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign in') }}</a>
    </p>
</div>
@endsection
