<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot access admin dashboard.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test panitia access control.
     */
    public function test_panitia_access_control(): void
    {
        $panitia = User::factory()->create([
            'role' => 'panitia',
        ]);
        
        // 1. Can access Panitia Dashboard
        $response = $this->actingAs($panitia)->get('/panitia');
        $response->assertStatus(200);

        // 2. Cannot access Admin Dashboard (Redirects back to Panitia Dashboard)
        $response = $this->actingAs($panitia)->get('/admin/dashboard');
        $response->assertRedirect(route('panitia.dashboard'));
    }
}
