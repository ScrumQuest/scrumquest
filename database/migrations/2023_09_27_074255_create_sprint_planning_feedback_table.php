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
        Schema::create('sprint_planning_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sprint_id')->constrained();
            $table->string('feedback_id', 255);
            $table->text('feedback');
            $table->integer('repeats')->default(0);
            $table->boolean('fixed_at_sprint_start')->default(false);
            $table->timestamps();

            $table->unique(['sprint_id', 'feedback_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprint_planning_feedback');
    }
};
