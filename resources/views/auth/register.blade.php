@extends('layouts.auth')

@section('title', 'Register')
@section('heading', 'Create your account')
@section('description', 'All your purchases in one place.')

@section('content')
<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Full name</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
               autocomplete="name" placeholder="Enter your full name" required
               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
        @error('name')
            <div id="name-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
               autocomplete="email" placeholder="name@example.com" required
               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
        @error('email')
            <div id="email-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
               autocomplete="new-password" placeholder="Create a password" required
               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
        @error('password')
            <div id="password-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
               autocomplete="new-password" placeholder="Repeat your password" required
               @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror>
        @error('password_confirmation')
            <div id="password_confirmation-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-2">Create account <span aria-hidden="true">→</span></button>
</form>
<p class="text-center text-body-secondary small border-top pt-4 mt-4 mb-0">
    Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Sign in</a>
</p>
@endsection
