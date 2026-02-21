<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop FK and Index safely
        try {
            DB::statement("ALTER TABLE dpm_votes DROP FOREIGN KEY dpm_votes_calon_dpm_id_foreign");
        } catch (\Exception $e) {
            // FK might not exist or name might be different
        }
        
        try {
            DB::statement("DROP INDEX dpm_votes_calon_dpm_id_foreign ON dpm_votes");
        } catch (\Exception $e) {
            // Index might not exist
        }

        Schema::table('dpm_votes', function (Blueprint $table) {
            $table->string('calon_dpm_id', 1024)->change();
            $table->json('encryption_meta')->nullable()->after('calon_dpm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dpm_votes', function (Blueprint $table) {
            $table->dropColumn('encryption_meta');
            $table->unsignedBigInteger('calon_dpm_id')->change();
        });
    }
};
