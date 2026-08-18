<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_setup_is_available_only_without_users(): void
    {
        $this->get('/configuracion-inicial')->assertOk();

        $this->post('/configuracion-inicial', [
            'identificador' => 'ADMINLOCAL1',
            'email_recuperacion' => 'admin@example.test',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ])->assertRedirect(route('login', absolute: false));

        $this->assertDatabaseHas('users', ['identificador' => 'ADMINLOCAL1', 'rol' => 'Administrador']);
        $this->get('/configuracion-inicial')->assertNotFound();
    }

    public function test_guest_cannot_open_administrative_registration(): void
    {
        User::factory()->create();
        $this->get('/usuarios/nuevo')->assertRedirect(route('login', absolute: false));
    }

    public function test_administrator_can_create_user(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/usuarios', [
            'identificador' => 'DIRECTOR01',
            'email_recuperacion' => 'director@example.test',
            'rol' => 'Director',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ])->assertRedirect(route('users.create', absolute: false));

        $this->assertDatabaseHas('users', ['identificador' => 'DIRECTOR01', 'rol' => 'Director']);
    }

    public function test_director_cannot_create_users(): void
    {
        $director = User::factory()->role('Director')->create();
        $this->actingAs($director)->get('/usuarios/nuevo')->assertForbidden();
    }
}
