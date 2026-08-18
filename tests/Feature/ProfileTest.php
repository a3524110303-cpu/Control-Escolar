<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/profile')->assertOk();
    }

    public function test_recovery_email_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'email_recuperacion' => 'nuevo@example.test',
        ])->assertSessionHasNoErrors()->assertRedirect(route('profile.edit', absolute: false));

        $this->assertSame('nuevo@example.test', $user->refresh()->email_recuperacion);
    }

    public function test_recovery_email_must_be_unique(): void
    {
        $other = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'email_recuperacion' => $other->email_recuperacion,
        ])->assertSessionHasErrors('email_recuperacion');
    }

    public function test_users_cannot_delete_their_own_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->delete('/profile', ['password' => 'Password123!'])
            ->assertMethodNotAllowed();

        $this->assertNotNull($user->fresh());
    }
}
