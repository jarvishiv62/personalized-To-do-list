<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            [
                'content' => 'Believe you can and you\'re halfway there.',
                'author' => 'Theodore Roosevelt',
            ],
            [
                'content' => 'Do something today that your future self will thank you for.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Small progress is still progress.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'The secret of getting ahead is getting started.',
                'author' => 'Mark Twain',
            ],
            [
                'content' => 'Don\'t watch the clock; do what it does. Keep going.',
                'author' => 'Sam Levenson',
            ],
            [
                'content' => 'The future depends on what you do today.',
                'author' => 'Mahatma Gandhi',
            ],
            [
                'content' => 'Success is the sum of small efforts repeated day in and day out.',
                'author' => 'Robert Collier',
            ],
            [
                'content' => 'You don\'t have to be great to start, but you have to start to be great.',
                'author' => 'Zig Ziglar',
            ],
            [
                'content' => 'The only way to do great work is to love what you do.',
                'author' => 'Steve Jobs',
            ],
            [
                'content' => 'A journey of a thousand miles begins with a single step.',
                'author' => 'Lao Tzu',
            ],
            [
                'content' => 'Your limitation—it\'s only your imagination.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Great things never come from comfort zones.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Dream it. Wish it. Do it.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Success doesn\'t just find you. You have to go out and get it.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'The harder you work for something, the greater you\'ll feel when you achieve it.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Dream bigger. Do bigger.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Don\'t stop when you\'re tired. Stop when you\'re done.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Wake up with determination. Go to bed with satisfaction.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Do something today that your future self will thank you for.',
                'author' => 'Sean Patrick Flanery',
            ],
            [
                'content' => 'Little things make big days.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'It\'s going to be hard, but hard does not mean impossible.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Don\'t wait for opportunity. Create it.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'Sometimes we\'re tested not to show our weaknesses, but to discover our strengths.',
                'author' => 'Unknown',
            ],
            [
                'content' => 'The key to success is to focus on goals, not obstacles.',
                'author' => 'Unknown',
            ],
        ];

        foreach ($quotes as $quote) {
            Quote::create($quote);
        }
    }
}