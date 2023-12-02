<?php

namespace App\Foundation\Puzzle;

class PuzzleSolver
{
    /* Keep track of the result */
    protected int $result = 0;

    protected array $cubeColours = [
        'red' => ['limit' => 12],
        'green' => ['limit' => 13],
        'blue' => ['limit' => 14],
    ];

    public function solve(string $game): void
    {
        // Extract what we need with regex.
        preg_match('/Game (?<id>\d+): (?<puzzle>.+)/i', $game, $components);
        preg_match_all('/((?<count>\d+) (?<colour>red|green|blue))/i', $components['puzzle'] ?? '', $cubes);

        // Do nothing if we don't have the expected data
        if (blank($components) || blank($cubes)) {
            return;
        }

        // Check if the game is possible based on cube limits.
        $possible = true;

        foreach ($cubes['colour'] as $i => $colour) {
            if ((int) $cubes['count'][$i] > $this->cubeColours[$colour]['limit']) {
                $possible = false;
                break;
            }
        }

        // Add to result if the game is possible.
        if ($possible) {
            $this->result += (int) $components['id'];
        }
    }

    public function getResult(): int
    {
        return $this->result;
    }
}
