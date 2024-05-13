<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_feedback', function (Blueprint $table) {
            $table->dropUnique('daily_feedback_feedback_day_feedback_id_backlog_item_id_unique');
            $table->dropForeign(['backlog_item_id']);
            $table->dropColumn('backlog_item_id');

            $table->foreignId('sprint_id')->constrained();

            $table->unique(['sprint_id', 'feedback_day', 'feedback_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_feedback', function (Blueprint $table) {
            $table->foreignId('backlog_item_id')->nullable()->constrained();
            $table->unique(['feedback_day', 'feedback_id', 'backlog_item_id']);

            $table->dropForeign(['sprint_id']);
            $table->dropColumn('sprint_id');
            $table->dropUnique('daily_feedback_sprint_id_feedback_day_feedback_id_unique');
        });
    }
};
