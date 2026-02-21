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
        Schema::table('calon_dpms', function (Blueprint $table) {
            $table->dropColumn(['nama_wakil', 'foto_wakil', 'fakultas_wakil', 'prodi_wakil']);
            
            // Optional: Rename ketua columns back to generic since it's single
            $table->renameColumn('nama_ketua', 'nama');
            $table->renameColumn('foto_ketua', 'foto');
            $table->renameColumn('fakultas_ketua', 'fakultas');
            $table->renameColumn('prodi_ketua', 'prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_dpms', function (Blueprint $table) {
            $table->string('nama_wakil')->nullable();
            $table->string('foto_wakil')->nullable();
            $table->string('fakultas_wakil')->nullable();
            $table->string('prodi_wakil')->nullable();

            $table->renameColumn('nama', 'nama_ketua');
            $table->renameColumn('foto', 'foto_ketua');
            $table->renameColumn('fakultas', 'fakultas_ketua');
            $table->renameColumn('prodi', 'prodi_ketua');
        });
    }
};
