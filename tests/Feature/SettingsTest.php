<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Setting;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_settings()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Setting::factory()->count(3)->create(['admin_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/settings');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_update_setting()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/settings', [
            'key' => 'appearance',
            'value' => 'classic orange',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Setting updated']);

        $this->assertDatabaseHas('settings', [
            'admin_id' => $admin->id,
            'key' => 'appearance',
            'value' => 'classic orange',
        ]);
    }

    public function test_admin_can_delete_setting()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $setting = Setting::factory()->create(['admin_id' => $admin->id, 'key' => 'appearance']);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/settings', [
            'key' => 'appearance',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Setting deleted']);

        $this->assertDatabaseMissing('settings', [
            'admin_id' => $admin->id,
            'key' => 'appearance',
        ]);
    }
}
