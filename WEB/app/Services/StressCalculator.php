<?php

namespace App\Services;

class StressCalculator
{
    public const LEVEL_HIGH = 'Élevé';
    public const LEVEL_MODERATE = 'Modéré';
    public const LEVEL_LOW = 'Faible';

    public function calculateScore(array $points): int
    {
        return array_sum($points);
    }

    public function determineLevel(int $score): string
    {
        if ($score >= 300) {
            return self::LEVEL_HIGH;
        }

        if ($score >= 150) {
            return self::LEVEL_MODERATE;
        }

        return self::LEVEL_LOW;
    }
}
