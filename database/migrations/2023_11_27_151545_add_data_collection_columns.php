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
        Schema::table('backlog_items', function ($table) {
            $table->date('original_planned_date')->nullable();
            $table->unsignedInteger('total_replans')->default(0);
            $table->unsignedInteger('reassignments')->default(0);
        });

        Schema::create('planning_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_item_id')->constrained();
            $table->date('planned_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('backlog_items', function ($table) {
            $table->dropColumn('original_planned_date');
            $table->dropColumn('total_replans');
            $table->dropColumn('reassignments');
        });

        Schema::dropIfExists('planning_histories');
    }
};
