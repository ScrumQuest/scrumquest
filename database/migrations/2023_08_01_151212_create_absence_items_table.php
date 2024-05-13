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
        Schema::create('absence_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignee_id');
            $table->foreign('assignee_id')->references('id')->on('users');
            $table->foreignId('sprint_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('week_in_sprint');
            $table->unsignedInteger('day_in_week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_items');
    }
};
