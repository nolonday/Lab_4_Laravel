<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_signin_page_renders_registration_form_with_csrf_token(): void
    {
        $response = $this->get(route('signin'));

        $response
            ->assertOk()
            ->assertViewIs('auth.signin')
            ->assertSee('method="POST"', false)
            ->assertSee('action="'.route('signin.registration').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('href="'.route('signin').'"', false)
            ->assertSee('Регистрация');
    }

    public function test_valid_registration_returns_json_without_exposing_password(): void
    {
        $response = $this->postJson(route('signin.registration'), [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'password' => 'secure-password',
        ]);

        $response
            ->assertOk()
            ->assertExactJson([
                'message' => 'Данные успешно проверены.',
                'data' => [
                    'name' => 'Иван Иванов',
                    'email' => 'ivan@example.com',
                ],
            ]);
    }

    public function test_invalid_registration_returns_validation_errors_as_json(): void
    {
        $response = $this->postJson(route('signin.registration'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_invalid_browser_submission_redirects_back_with_validation_errors(): void
    {
        $response = $this->from(route('signin'))->post(route('signin.registration'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response
            ->assertRedirect(route('signin'))
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
