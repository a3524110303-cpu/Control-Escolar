<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_login_for_guest(): void
    {
        $this->get('/')->assertRedirect(route('login', absolute: false));
    }

    public function test_health_endpoint_and_security_headers_are_available(): void
    {
        $this->get('/up')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_administrative_routes_require_roles(): void
    {
        $student = User::factory()->role('Alumno')->create();

        $this->actingAs($student)->get('/alumnos')->assertForbidden();
        $this->actingAs($student)->get('/docentes')->assertForbidden();
        $this->actingAs($student)->get('/academico')->assertForbidden();
    }

    public function test_removed_dangerous_routes_do_not_exist(): void
    {
        User::factory()->create();
        $this->get('/limpiar-sistema')->assertNotFound();
        $this->get('/areaAdministrativa')->assertNotFound();
    }
}
