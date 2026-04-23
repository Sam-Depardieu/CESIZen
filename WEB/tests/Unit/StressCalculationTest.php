<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class StressCalculationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_stress_score_calculation(): void
    {
        // 1. Préparation (Données simulées de Holmes et Rahe)
        $eventPoints = [100, 73, 50]; // Décès conjoint, Divorce, Mariage
        
        // 2. Action (La logique que tu veux tester)
        $totalScore = array_sum($eventPoints);

        // 3. Assertion (La vérification)
        $this->assertEquals(223, $totalScore, "Le calcul du score total a échoué.");
    }

    /**
     * Teste la détermination du niveau de stress.
     */
    public function test_stress_level_label(): void
    {
        $score = 250;
        $level = "";

        if ($score >= 300) {
            $level = "Risque élevé";
        } elseif ($score >= 150) {
            $level = "Risque modéré";
        } else {
            $level = "Risque léger";
        }

        $this->assertEquals("Risque modéré", $level);
    }
}
