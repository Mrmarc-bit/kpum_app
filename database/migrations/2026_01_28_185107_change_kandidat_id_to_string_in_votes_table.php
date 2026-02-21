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
        // 1. Drop FK Constraint safely
        try {
            DB::statement("ALTER TABLE votes DROP FOREIGN KEY votes_kandidat_id_foreign");
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        // 2. Drop Index safely
        try {
            DB::statement("DROP INDEX votes_kandidat_id_foreign ON votes");
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        // 3. Change column type
        Schema::table('votes', function (Blueprint $table) {
            $table->string('kandidat_id', 1024)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedBigInteger('kandidat_id')->change();
            
            // Restore FK if possible
            try {
                $table->foreign('kandidat_id')->references('id')->on('kandidats')->onDelete('cascade');
            } catch (\Exception $e) {}
        });
    }
};
