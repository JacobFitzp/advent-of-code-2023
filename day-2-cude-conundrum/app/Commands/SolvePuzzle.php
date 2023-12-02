<?php

namespace App\Commands;

use App\Foundation\Puzzle\FewestCubesSolver;
use LaravelZero\Framework\Commands\Command;
use App\Foundation\Puzzle\PossibleGamesSolver;
use SplFileObject;

class SolvePuzzle extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'solve-puzzle';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Solve cube conundrum puzzle';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $puzzleInputPath = $this->ask('Puzzle input path');

        // Check if the provided puzzle file exists
        if (!file_exists($puzzleInputPath)) {
            $this->error("Puzzle input file '$puzzleInputPath' not found");
            return;
        }

        $solver = $this->output->choice('How should the puzzle be solved?', [
            PossibleGamesSolver::class => 'Sum of possible game IDs',
            FewestCubesSolver::class => 'Sum of power of fewest number of cubes',
        ]);

        // Open the puzzle input file
        $file = new SplFileObject($puzzleInputPath);

        // Count the number of lines in the puzzle input file
        $file->seek($file->getSize());
        $lineCount = $file->key();

        // Rewind to start of the file
        $file->rewind();

        // Initialize the puzzle solver
        $solver = new $solver;

        // Display progress bar
        $this->output->progressStart($lineCount);

        // Read the puzzle input file one line at a time
        // Each line will then be solved
        while (!$file->eof()) {
            $solver->solve($file->fgets());
            $this->output->progressAdvance();
        }

        // Output result
        $this->output->progressFinish();
        $this->output->success("Result: {$solver->getResult()}");
    }
}
