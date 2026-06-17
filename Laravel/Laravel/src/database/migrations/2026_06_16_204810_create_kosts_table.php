<?php

declare(strict_types=1);

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
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();
            $table->string('lantai', 1);
            $table->string('nomor_kamar', 10);
            $table->string('nama_penyewa')->nullable();
            $table->enum('status', ['kosong', 'terisi'])->default('kosong');
            $table->timestamps();

            // Index untuk performa query
            $table->index(['lantai', 'nomor_kamar']);
            $table->index('status');

            // Unique constraint biar tidak duplikat
            $table->unique(['lantai', 'nomor_kamar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};
