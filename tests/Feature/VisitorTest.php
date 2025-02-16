<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Visitor;

class VisitorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_visitors()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Visitor::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/visitors');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_create_visitor()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/visitors', [
            'name' => 'John Doe',
            'contact_number' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visitor created successfully']);

        $this->assertDatabaseHas('visitors', [
            'name' => 'John Doe',
            'contact_number' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
        ]);
    }

    public function test_admin_can_update_visitor()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visitor = Visitor::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/visitors/' . $visitor->id, [
            'name' => 'Jane Doe',
            'contact_number' => '0987654321',
            'email' => 'jane@example.com',
            'address' => '456 Main St',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visitor updated successfully']);

        $this->assertDatabaseHas('visitors', [
            'id' => $visitor->id,
            'name' => 'Jane Doe',
            'contact_number' => '0987654321',
            'email' => 'jane@example.com',
            'address' => '456 Main St',
        ]);
    }

    public function test_admin_can_delete_visitor()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visitor = Visitor::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/visitors/' . $visitor->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visitor deleted successfully']);

        $this->assertDatabaseMissing('visitors', [
            'id' => $visitor->id,
        ]);
    }

    public function test_admin_can_mark_visitor_as_vip()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visitor = Visitor::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/visitors/' . $visitor->id . '/vip');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visitor marked as VIP']);

        $this->assertDatabaseHas('visitors', [
            'id' => $visitor->id,
            'is_vip' => true,
        ]);
    }

    public function test_admin_can_blacklist_visitor()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $visitor = Visitor::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/visitors/' . $visitor->id . '/blacklist');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Visitor blacklisted']);

        $this->assertDatabaseHas('visitors', [
            'id' => $visitor->id,
            'is_blacklisted' => true,
        ]);
    }
}
