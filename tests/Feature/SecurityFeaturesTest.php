<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AuditLog;
use App\Services\Salsa20;
use App\Services\Google2FA;
use App\Services\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_encrypts_and_decrypts_phone_number_using_salsa20()
    {
        $rawPhone = '081234567890';
        
        $user = User::factory()->create([
            'phone_number' => $rawPhone,
        ]);

        // Verify the accessor decrypts it back to raw
        $this->assertEquals($rawPhone, $user->phone_number);

        // Verify that the database stores it encrypted (not raw)
        $dbRow = \DB::table('users')->where('id', $user->id)->first();
        $this->assertNotEquals($rawPhone, $dbRow->phone_number);
        $this->assertStringStartsNotWith('0812', $dbRow->phone_number);

        // Manually decrypt using Salsa20 key to verify it matches
        $decrypted = Salsa20::decrypt($dbRow->phone_number, config('app.key'));
        $this->assertEquals($rawPhone, $decrypted);
    }

    /** @test */
    public function it_verifies_totp_keys_properly()
    {
        $secret = Google2FA::generateSecretKey();
        $this->assertEquals(16, strlen($secret));

        // Generate a code for the current time
        $timeSlice = floor(time() / 30);
        
        // Use a reflection or manual code generation helper to test verification
        $isValid = Google2FA::verifyKey($secret, '000000');
        $this->assertFalse($isValid);
    }

    /** @test */
    public function it_records_audit_logs()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        AuditLogger::log('User performed some crucial action');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'activity' => 'User performed some crucial action',
        ]);
    }
}
