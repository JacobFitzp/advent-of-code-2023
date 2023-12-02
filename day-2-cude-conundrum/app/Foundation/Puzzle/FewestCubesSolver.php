<?php

namespace App\Foundation\Puzzle;

class FewestCubesSolver extends PuzzleSolver
{
    public function solve(string $game): void
    {
        // Extract what we need with regex.
        preg_match('/Game (?<id>\d+): (?<puzzle>.+)/i', $game, $components);
        preg_match_all('/((?<counts>\d+) (?<colours>red|green|blue))/i', $components['puzzle'] ?? '', $cubes);

        // Do nothing if we don't have the expected data
        if (blank($components) || blank($cubes)) {
            return;
        }

        // Work out the fewest number of cubes for each colour.
        $fewestCubes = [];
        foreach ($cubes['colours'] as $i => $colour) {
            if (!isset($fewestCubes[$colour]) || (int) $cubes['counts'][$i] > $fewestCubes[$colour]) {
                $fewestCubes[$colour] = (int) $cubes['counts'][$i];
            }
        }

        // Multiply the results together to get power.
        $this->result += array_product($fewestCubes);
    }
}
