<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('section', ['daily', 'weekly', 'monthly'])
                ->default('daily')
                ->after('status');
            $table->unsignedBigInteger('goal_id')
                ->nullable()
                ->after('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'goal_id')) {
                $table->dropForeign(['goal_id']);
            }
            $table->dropColumn(['section', 'goal_id']);
        });
    }
};