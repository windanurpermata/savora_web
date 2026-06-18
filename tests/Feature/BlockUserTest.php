<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocked_user_cannot_login()
    {
        $user = User::create([
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'member',
            'is_blocked' => true,
        ]);

        $response = $this->post('/masuk', [
            'email' => 'blocked@example.com',
            'password' => 'Password123!',
            'g-recaptcha-response' => 'test-token',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_active_user_gets_logged_out_when_blocked()
    {
        $user = User::create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'member',
            'is_blocked' => false,
        ]);

        $this->actingAs($user);

        // Verify currently authenticated
        $this->assertAuthenticatedAs($user);

        // Block the user in database
        $user->update(['is_blocked' => true]);

        // Request any page (should trigger CheckBlocked middleware)
        $response = $this->get('/');

        // Should be logged out and redirected
        $this->assertGuest();
        $response->assertRedirect('/masuk');
    }
}
