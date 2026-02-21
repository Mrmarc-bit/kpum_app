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
        Schema::table('kandidats', function (Blueprint $table) {
            $table->string('foto_wakil')->nullable()->after('foto'); // Separate photo for wakil
            $table->string('fakultas_ketua')->nullable()->after('prodi_ketua');
            $table->string('fakultas_wakil')->nullable()->after('prodi_wakil');
            $table->string('slogan')->nullable()->after('misi');
            $table->string('deskripsi_singkat')->nullable()->after('slogan');
            $table->boolean('status_aktif')->default(true)->after('deskripsi_singkat');
            $table->boolean('tampilkan_di_landing')->default(true)->after('status_aktif');
            $table->integer('urutan_tampil')->default(0)->after('tampilkan_di_landing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kandidats', function (Blueprint $table) {
            $table->dropColumn([
                'foto_wakil',
                'fakultas_ketua',
                'fakultas_wakil',
                'slogan',
                'deskripsi_singkat',
                'status_aktif',
                'tampilkan_di_landing',
                'urutan_tampil'
            ]);
        });
    }
};
