<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot many-to-many: Kandidat ↔ Party (Koalisi Partai Pengusung)
     */
    public function up(): void
    {
        Schema::create('kandidat_party', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandidat_id')
                  ->constrained('kandidats')
                  ->onDelete('cascade'); // Hapus otomatis saat kandidat dihapus
            $table->foreignId('party_id')
                  ->constrained('parties')
                  ->onDelete('cascade'); // Hapus otomatis saat partai dihapus
            $table->timestamps();

            // Pastikan tidak ada duplikat koalisi yang sama
            $table->unique(['kandidat_id', 'party_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kandidat_party');
    }
};
