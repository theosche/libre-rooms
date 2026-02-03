@extends('layouts.app')

@section('title', __('Email verification'))

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-6">
            <svg class="mx-auto h-12 w-12 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ __('Verify your email') }}</h2>
        </div>

        <div class="text-center text-gray-600 mb-6">
            <p>{{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.') }}</p>
            <p class="mt-4">{{ __("If you didn't receive the email, we can send you a new one.") }}</p>
        </div>

        <div class="btn-group">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-full">
                    {{ __('Resend verification email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary w-full">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
