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
        Schema::table('timelines', function (Blueprint $table) {
            $table->string('status')->default('upcoming')->after('end_date');
            $table->dropColumn(['date', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timelines', function (Blueprint $table) {
            //
        });
    }
};
