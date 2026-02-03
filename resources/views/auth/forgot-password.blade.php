@extends('layouts.app')

@section('title', __('Forgot password?'))

@section('content')
<div class="auth-container container-full-form">
    <div class="form-header">
        <h1 class="form-title">{{ __('Forgot password?') }}</h1>
        <p class="form-subtitle">{{ __('Enter your email address and we will send you a link to reset your password.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="styled-form">
        @csrf

        <div class="form-group">
            <div class="form-element">
                <label for="email" class="form-element-title">{{ __('Email address') }}</label>
                <div class="form-field">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="your@email.com"
                    >
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">{{ __('Send reset link') }}</button>
        </div>
    </form>

    <p class="auth-link">
        <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
    </p>
</div>
@endsection
