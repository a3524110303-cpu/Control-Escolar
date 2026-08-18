<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_active_user_can_authenticate_with_identifier(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'identificador' => strtolower($user->identificador),
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->inactive()->create();

        $this->post('/login', [
            'identificador' => $user->identificador,
            'password' => 'Password123!',
        ])->assertSessionHasErrors('identificador');

        $this->assertGuest();
    }

    public function test_first_login_is_redirected_to_password_change(): void
    {
        $user = User::factory()->firstLogin()->create();

        $this->post('/login', [
            'identificador' => $user->identificador,
            'password' => 'Password123!',
        ])->assertRedirect(route('password.first.edit', absolute: false));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login', absolute: false));
        $this->assertGuest();
    }
}
