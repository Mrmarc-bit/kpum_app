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
            $table->string('details')->nullable()->after('type'); // Stores "Prodi: Informatika" etc.
            $table->integer('progress')->default(0)->after('status'); // 0-100
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_files', function (Blueprint $table) {
            $table->dropColumn(['details', 'progress']);
        });
    }
};
