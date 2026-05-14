<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StressEvent;
use App\Models\ResultatDiag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiagnosticTest extends TestCase
{
    use RefreshDatabase;

    public function test_diagnostic_score_calculation_and_storage(): void
    {
        $this->withoutMiddleware();

        // Créer un utilisateur
        $user = User::factory()->create();

        // Créer des événements de stress
        $event1 = StressEvent::create(['event_name' => 'Event 1', 'points' => 100]);
        $event2 = StressEvent::create(['event_name' => 'Event 2', 'points' => 60]);

        // Simuler une requête de diagnostic
        $response = $this->actingAs($user)
            ->post(route('diagnostics.store'), [
                'events' => [$event1->id, $event2->id]
            ]);

        // Vérifier la redirection et le score en session
        $response->assertStatus(302);
        $response->assertSessionHas('score', 160);

        // Vérifier l'enregistrement en base de données
        $this->assertDatabaseHas('resultat_diags', [
            'id_user' => $user->id,
            'score_total' => 160,
            'niveau_stress' => 'Modéré'
        ]);
    }
}
