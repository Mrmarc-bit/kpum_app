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
        Schema::create('calon_dpms', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_urut')->unique()->nullable(); // Boleh null jika belum diset
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->string('fakultas');
            $table->string('prodi');
            $table->text('deskripsi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->integer('urutan_tampil')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_dpms');
    }
};
