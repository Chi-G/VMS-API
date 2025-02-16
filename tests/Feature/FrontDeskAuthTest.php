<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\FrontDesk;
use Illuminate\Support\Facades\Hash;

class FrontDeskAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        FrontDesk::factory()->create([
            'email' => 'frontdesk@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_front_desk_user_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/front-desk/login', [
            'email' => 'frontdesk@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_front_desk_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/front-desk/login', [
            'email' => 'frontdesk@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Email not found']);
    }

    public function test_front_desk_user_can_request_password_reset()
    {
        $response = $this->postJson('/api/front-desk/forgot-password', [
            'email' => 'frontdesk@example.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'OTP sent to your email']);
    }

    public function test_front_desk_user_can_verify_otp()
    {
        $user = FrontDesk::where('email', 'frontdesk@example.com')->first();
        $user->otp = 123456;
        $user->save();

        $response = $this->postJson('/api/front-desk/verify-otp', [
            'email' => 'frontdesk@example.com',
            'otp' => 123456,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'OTP verified']);
    }

    public function test_front_desk_user_can_reset_password()
    {
        $user = FrontDesk::where('email', 'frontdesk@example.com')->first();
        $user->otp = 123456;
        $user->save();

        $response = $this->postJson('/api/front-desk/reset-password', [
            'email' => 'frontdesk@example.com',
            'otp' => 123456,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password reset successfully']);
    }
}
