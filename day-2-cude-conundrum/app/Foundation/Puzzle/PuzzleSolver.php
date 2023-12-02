<?php

namespace App\Foundation\Puzzle;

abstract class PuzzleSolver
{
    protected int $result = 0;

    public function getResult(): int
    {
        return $this->result;
    }

    abstract public function solve(string $game): void;
}
