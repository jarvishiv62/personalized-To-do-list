<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewSchedulerLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:logs {--lines=20 : Number of lines to display}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View the last scheduler log entries';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $logFile = storage_path('logs/scheduler.log');
        $lines = (int) $this->option('lines');

        if (!file_exists($logFile)) {
            $this->warn('No scheduler logs found yet.');
            $this->line('The log file will be created when the scheduler runs for the first time.');
            $this->line('Run: php artisan schedule:run');
            return self::SUCCESS;
        }

        $this->info("Displaying last {$lines} lines of scheduler logs:");
        $this->line(str_repeat('=', 60));

        // Read last N lines
        $file = new \SplFileObject($logFile, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        $startLine = max(0, $lastLine - $lines);

        $file->seek($startLine);
        while (!$file->eof()) {
            echo $file->current();
            $file->next();
        }

        $this->newLine();
        $this->line(str_repeat('=', 60));
        $this->info("Log file: {$logFile}");

        return self::SUCCESS;
    }
}