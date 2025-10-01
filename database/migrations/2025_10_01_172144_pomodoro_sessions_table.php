<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pomodoro_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['running', 'paused', 'completed'])->default('paused');
            $table->integer('focus_duration')->default(1500); // 25 minutes in seconds
            $table->integer('break_duration')->default(300);  // 5 minutes in seconds
            $table->integer('remaining_seconds')->default(1500);
            $table->boolean('is_break')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pomodoro_sessions');
    }
};