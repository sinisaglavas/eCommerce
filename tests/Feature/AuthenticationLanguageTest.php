<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_in_and_registration_pages_are_in_english(): void
    {
        $this->get(route('login'))->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('Welcome back')
            ->assertSee('Email address')
            ->assertSee('Password')
            ->assertSee('Remember me')
            ->assertSee('Sign in');

        $this->get(route('register'))->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('Create your account')
            ->assertSee('Full name')
            ->assertSee('Confirm password')
            ->assertSee('Create account');
    }

    public function test_invalid_login_returns_an_english_error(): void
    {
        $this->postJson('/login', [
            'email' => 'missing@example.com',
            'password' => 'IncorrectPassword!123',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email'])
            ->assertJsonPath('errors.email.0', 'These credentials do not match our records.');
    }

    public function test_registration_validation_errors_are_in_english(): void
    {
        $this->postJson(route('register.store'), [])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password'])
            ->assertJsonPath('errors.name.0', 'The name field is required.')
            ->assertJsonPath('errors.email.0', 'The email field is required.')
            ->assertJsonPath('errors.password.0', 'The password field is required.');

        $this->assertDatabaseCount('users', 0);
    }
}
