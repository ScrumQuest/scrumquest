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
        Schema::table('users', function ($table) {
            $table->string('github_id')->nullable();
            $table->string('auth_type')->nullable();
            $table->string('avatar_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('github_id');
            $table->dropColumn('auth_type');
            $table->dropColumn('avatar_link');
        });
    }
};
