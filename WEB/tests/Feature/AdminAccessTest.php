<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_non_admin_user_cannot_access_admin_panel(): void
    {
        $role = Role::create(['libelle' => 'Utilisateur']);
        $user = User::factory()->create(['id_role' => $role->id]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $role = Role::create(['libelle' => 'Admin']);
        $user = User::factory()->create(['id_role' => $role->id]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertSuccessful();
    }
}
