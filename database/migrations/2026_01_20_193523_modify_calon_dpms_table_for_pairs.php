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
        Schema::table('calon_dpms', function (Blueprint $table) {
            $table->renameColumn('nama', 'nama_ketua');
            $table->renameColumn('foto', 'foto_ketua');
            $table->renameColumn('fakultas', 'fakultas_ketua');
            $table->renameColumn('prodi', 'prodi_ketua');

            // Add columns for Wakil
            $table->string('nama_wakil')->nullable();
            $table->string('foto_wakil')->nullable();
            $table->string('fakultas_wakil')->nullable();
            $table->string('prodi_wakil')->nullable();

            // Additional fields similar to Kandidat if needed
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();

            // Optional: Rename deskripsi to something else or keep it as generic description
            // $table->renameColumn('deskripsi', 'deskripsi_singkat');
        });
    }

    public function down(): void
    {
        Schema::table('calon_dpms', function (Blueprint $table) {
            $table->renameColumn('nama_ketua', 'nama');
            $table->renameColumn('foto_ketua', 'foto');
            $table->renameColumn('fakultas_ketua', 'fakultas');
            $table->renameColumn('prodi_ketua', 'prodi');

            $table->dropColumn(['nama_wakil', 'foto_wakil', 'fakultas_wakil', 'prodi_wakil', 'visi', 'misi']);
        });
    }
};
