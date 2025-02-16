<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_fetch_staff()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        Staff::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/staff');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_create_staff()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/staff', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'Manager',
            'department' => 'HR',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Staff created successfully']);

        $this->assertDatabaseHas('staff', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'Manager',
            'department' => 'HR',
        ]);
    }

    public function test_admin_can_update_staff()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $staff = Staff::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/staff/' . $staff->id, [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'Supervisor',
            'department' => 'Finance',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Staff updated successfully']);

        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'Supervisor',
            'department' => 'Finance',
        ]);
    }

    public function test_admin_can_delete_staff()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $staff = Staff::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/staff/' . $staff->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Staff deleted successfully']);

        $this->assertDatabaseMissing('staff', [
            'id' => $staff->id,
        ]);
    }

    public function test_admin_can_deactivate_staff()
    {
        $admin = Admin::where('email', 'superadmin@example.com')->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        $staff = Staff::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/staff/' . $staff->id . '/deactivate');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Staff account deactivated']);

        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'status' => false,
        ]);
    }
}
