<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_link_can_be_requested_with_recovery_email(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email_recuperacion])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_screen_can_be_rendered(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email_recuperacion]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user): bool {
            $this->get('/reset-password/'.$notification->token.'?email='.urlencode($user->email_recuperacion))->assertOk();

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email_recuperacion]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user): bool {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email_recuperacion,
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ]);

            $response->assertSessionHasNoErrors()->assertRedirect(route('login', absolute: false));
            $this->assertTrue(Hash::check('NewPassword123!', $user->refresh()->password));

            return true;
        });
    }
}
