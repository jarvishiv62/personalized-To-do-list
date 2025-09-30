<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Goal;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get goals for associations
        $healthGoal = Goal::where('title', 'Stay Healthy & Active')->first();
        $projectGoal = Goal::where('title', 'Complete Laravel Project')->first();
        $learningGoal = Goal::where('title', 'Learn New Skills')->first();
        $personalGoal = Goal::where('title', 'Personal Development')->first();
        $organizeGoal = Goal::where('title', 'Organize Home Office')->first();
        $financeGoal = Goal::where('title', 'Financial Planning')->first();

        $tasks = [
            // Daily Tasks
            [
                'title' => 'Morning workout routine',
                'description' => '30 minutes cardio and strength training',
                'status' => 'completed',
                'section' => 'daily',
                'goal_id' => $healthGoal?->id,
                'due_date' => now(),
            ],
            [
                'title' => 'Read 10 pages of a book',
                'description' => 'Continue reading "Atomic Habits" - currently on chapter 3',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $personalGoal?->id,
                'due_date' => now(),
            ],
            [
                'title' => 'Practice meditation',
                'description' => '15 minutes of mindfulness meditation',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $personalGoal?->id,
                'due_date' => now(),
            ],
            [
                'title' => 'Drink 8 glasses of water',
                'description' => 'Stay hydrated throughout the day',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $healthGoal?->id,
                'due_date' => now(),
            ],
            [
                'title' => 'Review daily goals',
                'description' => 'Check progress and adjust priorities',
                'status' => 'completed',
                'section' => 'daily',
                'goal_id' => null,
                'due_date' => now(),
            ],

            // Weekly Tasks
            [
                'title' => 'Finish Laravel Stage 2 features',
                'description' => 'Complete goals dashboard and multi-section support',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $projectGoal?->id,
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'Grocery shopping',
                'description' => 'Buy vegetables, fruits, milk, eggs, and bread',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => null,
                'due_date' => now()->addDays(2),
            ],
            [
                'title' => 'Clean and organize desk',
                'description' => 'Remove clutter and organize paperwork',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $organizeGoal?->id,
                'due_date' => now()->addDays(4),
            ],
            [
                'title' => 'Complete online course module',
                'description' => 'Finish module 3 of Advanced Laravel course',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $learningGoal?->id,
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'Plan weekend activities',
                'description' => 'Research destinations and make reservations',
                'status' => 'completed',
                'section' => 'weekly',
                'goal_id' => null,
                'due_date' => now()->addDays(1),
            ],

            // Monthly Tasks
            [
                'title' => 'Review monthly budget',
                'description' => 'Analyze income, expenses, and savings',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $financeGoal?->id,
                'due_date' => now()->addDays(15),
            ],
            [
                'title' => 'Update portfolio website',
                'description' => 'Add new projects and update skills section',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $learningGoal?->id,
                'due_date' => now()->addDays(20),
            ],
            [
                'title' => 'Deep clean home office',
                'description' => 'Thorough cleaning and reorganization',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $organizeGoal?->id,
                'due_date' => now()->addDays(10),
            ],
            [
                'title' => 'Schedule health checkup',
                'description' => 'Book appointments with doctor and dentist',
                'status' => 'completed',
                'section' => 'monthly',
                'goal_id' => $healthGoal?->id,
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'Learn a new programming concept',
                'description' => 'Study design patterns and practice implementation',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $learningGoal?->id,
                'due_date' => now()->addDays(25),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }

        // Update goal progress after creating tasks
        Goal::all()->each(function ($goal) {
            $goal->calculateProgress();
        });
    }
}