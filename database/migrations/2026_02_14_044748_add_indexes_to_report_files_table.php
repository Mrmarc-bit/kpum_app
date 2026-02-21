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
        Schema::table('report_files', function (Blueprint $table) {
            // Add indexes for faster queries on letters page
            $table->index('user_id');
            $table->index('type');
            $table->index(['type', 'user_id']); // Composite index for common query
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_files', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['type', 'user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
    }
};
