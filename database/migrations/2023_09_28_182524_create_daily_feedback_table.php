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
        Schema::create('daily_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_item_id')->nullable()->constrained();
            $table->date('feedback_day');
            $table->string('feedback_id', 255);
            $table->text('feedback');
            $table->integer('repeats')->default(0);
            $table->boolean('fixed')->default(false);
            $table->timestamps();

            $table->unique(['feedback_day', 'feedback_id', 'backlog_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_feedback');
    }
};
