<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     * 
     * @param int $userId The ID of the user to associate tasks with
     */
    public function run($userId = null): void
    {
        if (!$userId) {
            $userId = User::first()?->id;
            if (!$userId) {
                throw new \RuntimeException('No user found. Please run the User seeder first.');
            }
        }

        // Get goals for associations
        $healthGoal = Goal::where('title', 'Stay Healthy & Active')
            ->where('user_id', $userId)
            ->first();
        $projectGoal = Goal::where('title', 'Complete Laravel Project')
            ->where('user_id', $userId)
            ->first();
        $learningGoal = Goal::where('title', 'Learn New Skills')
            ->where('user_id', $userId)
            ->first();
        $personalGoal = Goal::where('title', 'Personal Development')
            ->where('user_id', $userId)
            ->first();
        $organizeGoal = Goal::where('title', 'Organize Home Office')
            ->where('user_id', $userId)
            ->first();
        $financeGoal = Goal::where('title', 'Financial Planning')
            ->where('user_id', $userId)
            ->first();

        $tasks = [
            // Daily Tasks with Time Schedules
            [
                'title' => 'Morning workout routine',
                'description' => '30 minutes cardio and strength training',
                'status' => 'completed',
                'section' => 'daily',
                'goal_id' => $healthGoal ? $healthGoal->id : null,
                'due_date' => now(),
                'start_time' => '06:00',
                'end_time' => '06:45',
                'user_id' => $userId,
            ],
            [
                'title' => 'Meditation and mindfulness',
                'description' => '15 minutes of morning meditation',
                'status' => 'completed',
                'section' => 'daily',
                'goal_id' => $personalGoal ? $personalGoal->id : null,
                'due_date' => now(),
                'start_time' => '07:00',
                'end_time' => '07:15',
                'user_id' => $userId,
            ],
            [
                'title' => 'Review daily goals and priorities',
                'description' => 'Plan the day and set priorities',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => null,
                'due_date' => now(),
                'start_time' => '09:00',
                'end_time' => '09:30',
                'user_id' => $userId,
            ],
            [
                'title' => 'Work on Laravel project',
                'description' => 'Implement Stage 4 time scheduling features',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $projectGoal ? $projectGoal->id : null,
                'due_date' => now(),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'user_id' => $userId,
            ],
            [
                'title' => 'Lunch break',
                'description' => 'Healthy meal and short walk',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $healthGoal?->id,
                'due_date' => now(),
                'start_time' => '13:00',
                'end_time' => '14:00',
                'user_id' => $userId,
            ],
            [
                'title' => 'Study advanced Laravel concepts',
                'description' => 'Review documentation and practice coding',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $learningGoal ? $learningGoal->id : null,
                'due_date' => now(),
                'start_time' => '14:30',
                'end_time' => '16:00',
                'user_id' => $userId,
            ],
            [
                'title' => 'Read 10 pages of a book',
                'description' => 'Continue reading "Atomic Habits"',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $personalGoal ? $personalGoal->id : null,
                'due_date' => now(),
                'start_time' => '16:30',
                'end_time' => '17:00',
                'user_id' => $userId,
            ],
            [
                'title' => 'Evening exercise',
                'description' => 'Yoga or light stretching',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => $healthGoal?->id,
                'due_date' => now(),
                'start_time' => '18:00',
                'end_time' => '18:30',
            ],
            [
                'title' => 'Plan tomorrow\'s schedule',
                'description' => 'Review tasks and set priorities for next day',
                'status' => 'pending',
                'section' => 'daily',
                'goal_id' => null,
                'due_date' => now(),
                'start_time' => '21:00',
                'end_time' => '21:15',
            ],

            // Weekly Tasks
            [
                'title' => 'Finish Laravel Stage 4 features',
                'description' => 'Complete time scheduling implementation',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $projectGoal ? $projectGoal->id : null,
                'due_date' => now()->addDays(3),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Grocery shopping',
                'description' => 'Buy vegetables, fruits, milk, eggs, and bread',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => null,
                'due_date' => now()->addDays(2),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Clean and organize desk',
                'description' => 'Remove clutter and organize paperwork',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $organizeGoal ? $organizeGoal->id : null,
                'due_date' => now()->addDays(4),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Complete online course module',
                'description' => 'Finish module 4 of Advanced Laravel course',
                'status' => 'pending',
                'section' => 'weekly',
                'goal_id' => $learningGoal ? $learningGoal->id : null,
                'due_date' => now()->addDays(5),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Weekend hiking trip',
                'description' => 'Plan and execute outdoor activity',
                'status' => 'completed',
                'section' => 'weekly',
                'goal_id' => $healthGoal?->id,
                'due_date' => now()->addDays(1),
                'start_time' => null,
                'end_time' => null,
            ],

            // Monthly Tasks
            [
                'title' => 'Review monthly budget',
                'description' => 'Analyze income, expenses, and savings',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $financeGoal ? $financeGoal->id : null,
                'due_date' => now()->addDays(15),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Update portfolio website',
                'description' => 'Add new projects and update skills section',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $learningGoal ? $learningGoal->id : null,
                'due_date' => now()->addDays(20),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Deep clean home office',
                'description' => 'Thorough cleaning and reorganization',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $organizeGoal ? $organizeGoal->id : null,
                'due_date' => now()->addDays(10),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Health checkup appointments',
                'description' => 'Visit doctor and dentist for routine checkups',
                'status' => 'completed',
                'section' => 'monthly',
                'goal_id' => $healthGoal?->id,
                'due_date' => now()->addDays(7),
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'title' => 'Learn a new programming pattern',
                'description' => 'Study repository pattern and implement in project',
                'status' => 'pending',
                'section' => 'monthly',
                'goal_id' => $learningGoal ? $learningGoal->id : null,
                'due_date' => now()->addDays(25),
                'start_time' => null,
                'end_time' => null,
            ],
        ];

        foreach ($tasks as $taskData) {
            // Ensure user_id is set for each task
            $taskData['user_id'] = $userId;
            Task::create($taskData);
        }

        // Update goal progress after creating tasks
        Goal::all()->each(function ($goal) {
            $goal->calculateProgress();
        });
    }
}