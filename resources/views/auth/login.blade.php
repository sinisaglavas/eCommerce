@extends('layouts.auth')

@section('title', 'Prijava')
@section('heading', 'Dobro došli nazad')
@section('description', 'Prijavite se na svoj nalog i nastavite kupovinu.')

@section('content')
<form method="POST" action="{{ url('/login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email adresa</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
               autocomplete="email" placeholder="ime@primer.com" required
               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
        @error('email')
            <div id="email-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Lozinka</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
               autocomplete="current-password" placeholder="Unesite lozinku" required
               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
        @error('password')
            <div id="password-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
        <label class="form-check-label small text-body-secondary" for="remember">Zapamti me</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Prijavi se <span aria-hidden="true">→</span></button>
</form>
@if (Route::has('register'))
    <p class="text-center text-body-secondary small border-top pt-4 mt-4 mb-0">
        Nemate nalog? <a href="{{ route('register') }}" class="fw-semibold">Registrujte se</a>
    </p>
@endif
@endsection
