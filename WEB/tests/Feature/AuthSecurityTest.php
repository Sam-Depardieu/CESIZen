<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la BDD de test à chaque lancement

    #[Test]
    public function un_utilisateur_non_authentifie_est_bloque()
    {
        // On tente d'accéder à la route de profil sans token
        $response = $this->postJson('/api/update-profile');

        $response->assertStatus(401);
    }

    #[Test]
    public function un_utilisateur_authentifie_peut_acceder_a_son_profil()
    {
        // 1. Préparation : Créer un rôle et un utilisateur
        $role = Role::create(['libelle' => 'Utilisateur']);
        $user = User::factory()->create(['id_role' => $role->id]);

        // 2. Action : Simuler une connexion avec Sanctum
        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/profile'); // Remplace par une de tes routes protégées

        // 3. Assertion : Vérifier que l'accès est accordé (200)
        $response->assertStatus(200);
    }
}