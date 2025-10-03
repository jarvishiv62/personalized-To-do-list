<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     * 
     * @param int $userId The ID of the user to associate goals with
     */
    public function run($userId = null): void
    {
        if (!$userId) {
            $userId = User::first()?->id;
            if (!$userId) {
                throw new \RuntimeException('No user found. Please run the User seeder first.');
            }
        }

        $goals = [
            [
                'title' => 'Stay Healthy & Active',
                'description' => 'Maintain a healthy lifestyle through regular exercise and proper nutrition',
                'section' => 'daily',
                'progress' => 0,
                'user_id' => $userId,
            ],
            [
                'title' => 'Complete Laravel Project',
                'description' => 'Finish building the DailyDrive application with all features',
                'section' => 'weekly',
                'progress' => 0,
                'user_id' => $userId,
            ],
            [
                'title' => 'Learn New Skills',
                'description' => 'Dedicate time to learning new technologies and improving existing skills',
                'section' => 'monthly',
                'progress' => 0,
                'user_id' => $userId,
            ],
            [
                'title' => 'Personal Development',
                'description' => 'Read books, practice mindfulness, and work on self-improvement',
                'section' => 'daily',
                'progress' => 0,
                'user_id' => $userId,
            ],
            [
                'title' => 'Organize Home Office',
                'description' => 'Declutter and organize workspace for better productivity',
                'section' => 'weekly',
                'progress' => 0,
                'user_id' => $userId,
            ],
            [
                'title' => 'Financial Planning',
                'description' => 'Review budget, track expenses, and plan savings',
                'section' => 'monthly',
                'progress' => 0,
                'user_id' => $userId,
            ],
        ];

        foreach ($goals as $goal) {
            Goal::create($goal);
        }
    }
}