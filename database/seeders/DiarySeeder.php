<?php

namespace Database\Seeders;

use App\Models\Diary;
use Illuminate\Database\Seeder;

class DiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            [
                'user_id' => null,
                'title' => 'Starting My Productivity Journey',
                'content' => "Today marks the beginning of a new chapter. I've decided to take control of my time and focus on what truly matters. Setting up DailyDrive feels like the right step - a place to organize my thoughts, track my goals, and document my progress.\n\nI'm excited but also a bit nervous. Change is always challenging, but I know this will help me stay accountable. Looking forward to seeing where this journey takes me.",
                'date' => now()->subDays(7),
            ],
            [
                'user_id' => null,
                'title' => 'Reflecting on the Week',
                'content' => "This week has been interesting. I completed most of my daily tasks, though I struggled with time management on Tuesday. The pomodoro technique really helped on Thursday - I should use it more often.\n\nI'm grateful for:\n- Completing the Laravel project milestone\n- My morning workout routine\n- Quality time with family\n\nNext week, I want to focus on starting earlier and taking proper breaks.",
                'date' => now()->subDays(5),
            ],
            [
                'user_id' => null,
                'title' => 'A Productive Monday',
                'content' => "Started the day with meditation and a morning run - what a difference it makes! Felt energized throughout the day.\n\nAccomplished:\n- Finished three major tasks\n- Had a great team meeting\n- Learned something new about Laravel\n\nFeeling motivated to keep this momentum going. The key is consistency, not perfection.",
                'date' => now()->subDays(3),
            ],
            [
                'user_id' => null,
                'title' => 'Challenges and Lessons',
                'content' => "Today didn't go as planned. Overslept, missed my morning routine, and felt behind all day. But that's okay - it's about progress, not perfection.\n\nWhat I learned:\n- Need to set multiple alarms\n- Evening preparation makes mornings easier\n- One bad day doesn't define the week\n\nTomorrow is a fresh start. I'll do better.",
                'date' => now()->subDays(2),
            ],
            [
                'user_id' => null,
                'title' => 'Weekend Reflections',
                'content' => "Took some time today to step back and reflect on my goals. Am I still aligned with what I want to achieve? The answer is yes, but I need to adjust my approach.\n\nInsights:\n- Quality over quantity in task completion\n- Need more balance between work and rest\n- Journaling like this is actually helping me think clearly\n\nPlanning to revise my weekly goals tomorrow with these insights in mind.",
                'date' => now()->subDays(1),
            ],
            [
                'user_id' => null,
                'title' => 'Today\'s Thoughts',
                'content' => "Another day, another opportunity to grow. I'm starting to see patterns in my productivity - I work best in the mornings, need movement breaks, and thrive with clear deadlines.\n\nDailyDrive is really helping me stay organized. Seeing my tasks and diary entries on the calendar gives me a clear picture of how I'm spending my time.\n\nGrateful for this tool and the discipline it's helping me build.",
                'date' => now(),
            ],
        ];

        foreach ($entries as $entry) {
            Diary::create($entry);
        }
    }
}