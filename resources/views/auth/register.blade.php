@extends('layouts.auth')

@section('title', 'Registracija')
@section('heading', 'Kreirajte svoj nalog')
@section('description', 'Sve vaše kupovine na jednom mestu.')

@section('content')
<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Ime i prezime</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
               autocomplete="name" placeholder="Unesite ime i prezime" required
               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
        @error('name')
            <div id="name-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
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
               autocomplete="new-password" placeholder="Kreirajte lozinku" required
               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
        @error('password')
            <div id="password-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Potvrdite lozinku</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
               autocomplete="new-password" placeholder="Ponovite lozinku" required
               @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror>
        @error('password_confirmation')
            <div id="password_confirmation-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-2">Kreiraj nalog <span aria-hidden="true">→</span></button>
</form>
<p class="text-center text-body-secondary small border-top pt-4 mt-4 mb-0">
    Već imate nalog? <a href="{{ route('login') }}" class="fw-semibold">Prijavite se</a>
</p>
@endsection
