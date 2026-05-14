<?php

namespace Tests\Unit;

use App\Services\StressCalculator;
use PHPUnit\Framework\TestCase;

class StressCalculatorTest extends TestCase
{
    private StressCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new StressCalculator();
    }

    public function test_calculate_score_sums_points_correctly(): void
    {
        $points = [100, 73, 50];
        $this->assertEquals(223, $this->calculator->calculateScore($points));
    }

    public function test_calculate_score_returns_zero_for_empty_array(): void
    {
        $this->assertEquals(0, $this->calculator->calculateScore([]));
    }

    /**
     * @dataProvider stressLevelProvider
     */
    public function test_determine_level_returns_correct_label(int $score, string $expectedLevel): void
    {
        $this->assertEquals($expectedLevel, $this->calculator->determineLevel($score));
    }

    public static function stressLevelProvider(): array
    {
        return [
            [350, StressCalculator::LEVEL_HIGH],
            [300, StressCalculator::LEVEL_HIGH],
            [299, StressCalculator::LEVEL_MODERATE],
            [150, StressCalculator::LEVEL_MODERATE],
            [149, StressCalculator::LEVEL_LOW],
            [0, StressCalculator::LEVEL_LOW],
        ];
    }
}
