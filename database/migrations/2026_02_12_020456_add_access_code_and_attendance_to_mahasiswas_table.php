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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('access_code')->unique()->nullable()->after('password');
            $table->timestamp('attended_at')->nullable()->after('voted_at');
            $table->unsignedBigInteger('attendance_officer')->nullable()->after('attended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['access_code', 'attended_at', 'attendance_officer']);
        });
    }
};
