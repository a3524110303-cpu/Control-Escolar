<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmation_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/confirm-password')->assertOk();
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/confirm-password', ['password' => 'Password123!'])
            ->assertSessionHasNoErrors();

        $this->assertNotNull(session('auth.password_confirmed_at'));
    }

    public function test_invalid_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/confirm-password', ['password' => 'incorrecta'])
            ->assertSessionHasErrors('password');
    }
}
