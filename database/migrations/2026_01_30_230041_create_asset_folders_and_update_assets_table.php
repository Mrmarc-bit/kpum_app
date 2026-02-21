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
        // 1. Buat tabel Folders
        Schema::create('asset_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('asset_folders')->onDelete('cascade'); // Support Nested Folder
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Creator
            $table->timestamps();
        });

        // 2. Tambahkan folder_id ke tabel Assets
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('folder_id')->nullable()->after('id')->constrained('asset_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn('folder_id');
        });

        Schema::dropIfExists('asset_folders');
    }
};
