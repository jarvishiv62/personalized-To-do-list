<?php

namespace App\Console\Commands;

use App\Models\Goal;
use Illuminate\Console\Command;

class RecalculateGoalProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:recalculate-progress {--goal_id= : Specific goal ID to recalculate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate progress for all goals or a specific goal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $goalId = $this->option('goal_id');

        if ($goalId) {
            $goal = Goal::find($goalId);

            if (!$goal) {
                $this->error("Goal with ID {$goalId} not found.");
                return self::FAILURE;
            }

            $progress = $goal->calculateProgress();
            $this->info("Progress recalculated for goal: {$goal->title}");
            $this->line("New progress: {$progress}%");

            return self::SUCCESS;
        }

        // Recalculate all goals
        $this->info('Recalculating progress for all goals...');

        $goals = Goal::all();
        $bar = $this->output->createProgressBar($goals->count());
        $bar->start();

        foreach ($goals as $goal) {
            $goal->calculateProgress();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully recalculated progress for {$goals->count()} goals.");

        return self::SUCCESS;
    }
}