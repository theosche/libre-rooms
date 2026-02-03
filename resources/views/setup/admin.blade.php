@extends('layouts.app')

@section('title', __('Initial setup'))

@section('content')
<div class="auth-container container-full-form">
    <div class="form-header">
        <h1 class="form-title">{{ __('Initial setup') }}</h1>
        <p class="form-subtitle">{{ __('Create the first administrator account') }}</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">{{ __('Welcome to the reservation application!') }}</p>
                <p>{{ __('No administrator has been configured. Create the first administrator account to start using the application.') }}</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="error-messages">
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('setup.admin.store') }}" class="styled-form">
        @csrf

        <div class="form-group">
            <h3 class="form-group-title">{{ __('Administrator account') }}</h3>

            <div class="form-element">
                <label for="name" class="form-element-title">{{ __('Account name') }}</label>
                <div class="form-field">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="{{ __('Your full name') }}"
                    >
                </div>
            </div>

            <div class="form-element">
                <label for="email" class="form-element-title">Email</label>
                <div class="form-field">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="admin@example.com"
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
                        minlength="12"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ __('Minimum :count characters', ['count' => 12]) }}</p>
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

        <div class="btn-group justify-end mt-6">
            <button type="submit" class="btn btn-primary">
                {{ __('Create administrator account') }}
            </button>
        </div>
    </form>
</div>
@endsection
