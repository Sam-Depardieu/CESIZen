<?php

namespace Tests\Feature;

use App\Models\EvenementStress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BusinessLogicTest extends TestCase
{
    #[Test]
    public function la_liste_des_evenements_de_stress_est_accessible()
    {
        // On crée un événement simulé
        EvenementStress::create(['libelle' => 'Mariage', 'points' => 50]);

        $response = $this->getJson('/api/stress-events');

        $response->assertStatus(200)
                ->assertJsonFragment(['libelle' => 'Mariage']);
    }
}
