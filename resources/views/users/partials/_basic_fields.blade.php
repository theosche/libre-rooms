@php
    $showPasswordFields = $showPasswordFields ?? true;
@endphp

<div class="form-group">
    <h3 class="form-group-title">{{ __('Personal information') }}</h3>

    <fieldset class="form-element">
        <div class="form-field">
            <label for="name" class="form-element-title">{{ __('Account name') }}</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user?->name) }}"
                required
            >
            @error('name')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </fieldset>

    <fieldset class="form-element">
        <div class="form-field">
            <label for="email" class="form-element-title">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user?->email) }}"
                required
            >
            @if(request()->routeIs('users.edit'))
                <small class="text-gray-600">{{ __('Email is automatically verified for accounts created by an admin') }}</small>
            @else
                <small class="text-gray-600">{{ __('If you change your email, you will need to verify it again') }}</small>
            @endif
            @error('email')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </fieldset>
</div>

@if($showPasswordFields)
<div class="form-group">
    <h3 class="form-group-title">{{ __('Change password (optional)') }}</h3>
    <fieldset class="form-element">
        <div class="form-field">
            <label for="password" class="form-element-title">
                {{ __('Password') }}
                @if(isset($user))
                    <span class="text-xs text-gray-500">{{ __('(leave blank to keep current)') }}</span>
                @endif
            </label>
            <input
                type="password"
                id="password"
                name="password"
                @if(!isset($user)) required @endif
            >
            <small class="text-gray-600">{{ __('Minimum 12 characters') }}</small>
            @error('password')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </fieldset>

    <fieldset class="form-element">
        <div class="form-field">
            <label for="password_confirmation" class="form-element-title">{{ __('Confirm password') }}</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                @if(!isset($user)) required @endif
            >
        </div>
    </fieldset>
</div>
@endif
