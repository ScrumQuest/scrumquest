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
        Schema::create('backlog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained();
            $table->unsignedBigInteger('project_number');
            $table->unique(['project_id', 'project_number']);
            $table->text('title');
            $table->text('description');
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->foreign('assignee_id')->references('id')->on('users');
            $table->foreignId('sprint_id')->nullable()->constrained();
            $table->unsignedInteger('week_in_sprint')->nullable();
            $table->unsignedInteger('day_in_week')->nullable();
            $table->boolean('completed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlog_items');
    }
};
