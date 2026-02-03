@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
<div class="auth-container container-full-form">
    <div class="form-header">
        <h1 class="form-title">{{ __('Reset Password') }}</h1>
    </div>

    @if($errors->any())
        <div class="error-messages">
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="styled-form">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <div class="form-element">
                <label for="email" class="form-element-title">{{ __('Email address') }}</label>
                <div class="form-field">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-element">
                <label for="password" class="form-element-title">{{ __('New password') }}</label>
                <div class="form-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ __('Minimum :count characters', ['count' => 12]) }}</p>
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
            <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
        </div>
    </form>
</div>
@endsection
