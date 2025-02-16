<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Visitor;
use App\Models\Staff;
use App\Models\Visit;

class VisitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_visits()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Visit::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/visits');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_create_visit()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visitor = Visitor::factory()->create();
        $staff = Staff::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/visits', [
            'visitor_id' => $visitor->id,
            'staff_id' => $staff->id,
            'visitor_type' => 'individual',
            'purpose' => 'business meeting',
            'check_in' => now(),
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visit created successfully']);

        $this->assertDatabaseHas('visits', [
            'visitor_id' => $visitor->id,
            'staff_id' => $staff->id,
            'visitor_type' => 'individual',
            'purpose' => 'business meeting',
        ]);
    }

    public function test_admin_can_update_visit()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visit = Visit::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/visits/' . $visit->id, [
            'visitor_type' => 'group',
            'purpose' => 'recruitment',
            'check_in' => now(),
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visit updated successfully']);

        $this->assertDatabaseHas('visits', [
            'id' => $visit->id,
            'visitor_type' => 'group',
            'purpose' => 'recruitment',
        ]);
    }

    public function test_admin_can_delete_visit()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visit = Visit::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/visits/' . $visit->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visit deleted successfully']);

        $this->assertDatabaseMissing('visits', [
            'id' => $visit->id,
        ]);
    }
}
