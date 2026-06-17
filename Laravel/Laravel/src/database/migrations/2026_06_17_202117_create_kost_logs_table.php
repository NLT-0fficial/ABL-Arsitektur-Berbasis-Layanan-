<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kost_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->string('nama_penyewa');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('token_used', 64);
            $table->timestamp('scanned_at');
            $table->timestamps();

            $table->index('kost_id');
            $table->index('scanned_at');
            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost_logs');
    }
};