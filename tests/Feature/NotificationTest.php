<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Notification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_notifications()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Notification::factory()->count(3)->create(['admin_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/notifications');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_mark_notification_as_read()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $notification = Notification::factory()->create(['admin_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/notifications/mark-as-read', ['id' => $notification->id]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Notification marked as read']);

        $this->assertTrue($notification->fresh()->read);
    }

    public function test_admin_can_clear_all_notifications()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Notification::factory()->count(3)->create(['admin_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/notifications/clear');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'All notifications cleared']);

        $this->assertCount(0, $admin->notifications);
    }
}
