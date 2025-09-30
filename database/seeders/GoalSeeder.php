<?php

namespace Database\Seeders;

use App\Models\Goal;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $goals = [
            [
                'title' => 'Stay Healthy & Active',
                'description' => 'Maintain a healthy lifestyle through regular exercise and proper nutrition',
                'section' => 'daily',
                'progress' => 0,
            ],
            [
                'title' => 'Complete Laravel Project',
                'description' => 'Finish building the DailyDrive application with all features',
                'section' => 'weekly',
                'progress' => 0,
            ],
            [
                'title' => 'Learn New Skills',
                'description' => 'Dedicate time to learning new technologies and improving existing skills',
                'section' => 'monthly',
                'progress' => 0,
            ],
            [
                'title' => 'Personal Development',
                'description' => 'Read books, practice mindfulness, and work on self-improvement',
                'section' => 'daily',
                'progress' => 0,
            ],
            [
                'title' => 'Organize Home Office',
                'description' => 'Declutter and organize workspace for better productivity',
                'section' => 'weekly',
                'progress' => 0,
            ],
            [
                'title' => 'Financial Planning',
                'description' => 'Review budget, track expenses, and plan savings',
                'section' => 'monthly',
                'progress' => 0,
            ],
        ];

        foreach ($goals as $goal) {
            Goal::create($goal);
        }
    }
}