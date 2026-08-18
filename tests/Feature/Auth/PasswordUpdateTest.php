<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/password', [
            'current_password' => 'Password123!',
            'password' => 'UpdatedPassword123!',
            'password_confirmation' => 'UpdatedPassword123!',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('UpdatedPassword123!', $user->refresh()->password));
    }

    public function test_first_access_must_change_password(): void
    {
        $user = User::factory()->firstLogin()->create();

        $this->actingAs($user)->get('/dashboard')
            ->assertRedirect(route('password.first.edit', absolute: false));

        $this->actingAs($user)->put('/primer-acceso', [
            'current_password' => 'Password123!',
            'password' => 'UpdatedPassword123!',
            'password_confirmation' => 'UpdatedPassword123!',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertFalse($user->refresh()->primer_ingreso);
    }

    public function test_current_password_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/password', [
            'current_password' => 'incorrecta',
            'password' => 'UpdatedPassword123!',
            'password_confirmation' => 'UpdatedPassword123!',
        ])->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }
}
