<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\SendOtpVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_generates_and_sends_otp()
    {
        Notification::fake();

        $response = $this->post('/daftar', [
            'role' => 'member',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'g-recaptcha-response' => 'test-token',
        ]);

        $response->assertRedirect('/email/verify');

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->otp_code);
        $this->assertEquals(6, strlen($user->otp_code));
        $this->assertNotNull($user->otp_expires_at);

        Notification::assertSentTo($user, SendOtpVerification::class);
    }

    public function test_user_can_verify_email_with_correct_otp()
    {
        $user = User::create([
            'name' => 'Verify Test',
            'email' => 'verify@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'email_verified_at' => null,
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post('/email/verify', [
            'otp' => '123456',
        ]);

        $response->assertRedirect('/');
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->otp_code);
        $this->assertNull($user->otp_expires_at);
    }

    public function test_user_cannot_verify_email_with_incorrect_otp()
    {
        $user = User::create([
            'name' => 'Verify Test',
            'email' => 'verify@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'email_verified_at' => null,
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post('/email/verify', [
            'otp' => '654321',
        ]);

        $response->assertSessionHasErrors('otp');
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_user_cannot_verify_email_with_expired_otp()
    {
        $user = User::create([
            'name' => 'Verify Test',
            'email' => 'verify@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'email_verified_at' => null,
            'otp_code' => '123456',
            'otp_expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($user)->post('/email/verify', [
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors('otp');
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }
}
