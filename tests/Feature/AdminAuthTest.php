<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'superadmin@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'admin']);
    }

    public function test_admin_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'superadmin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Invalid credentials']);
    }

    public function test_admin_can_fetch_profile()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/admin/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'email', 'role']);
    }
}
