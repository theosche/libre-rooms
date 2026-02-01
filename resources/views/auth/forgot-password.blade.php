@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="auth-container container-full-form">
    <div class="form-header">
        <h1 class="form-title">Mot de passe oublié ?</h1>
        <p class="form-subtitle">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="styled-form">
        @csrf

        <div class="form-group">
            <div class="form-element">
                <label for="email" class="form-element-title">Adresse email</label>
                <div class="form-field">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="votre@email.com"
                    >
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
        </div>
    </form>

    <p class="auth-link">
        <a href="{{ route('login') }}">Retour à la connexion</a>
    </p>
</div>
@endsection
