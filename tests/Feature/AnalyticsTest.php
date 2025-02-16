<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Visit;
use App\Models\Staff;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase; 

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_analytics()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Visit::factory()->count(10)->create(['admin_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/analytics');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'visits',
                     'most_visited_staff',
                     'peak_visitor_hours',
                     'visitor_types',
                     'purpose_of_visit',
                     'most_frequent_visitors',
                 ]);
    }

    public function test_admin_can_export_analytics()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/analytics/export?format=pdf');

        $response->assertStatus(200);
        // Additional assertions for export content can be added here
    }
}
