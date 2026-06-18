<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kost_qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->cascadeOnDelete();
            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();   // <-- TAMBAHKAN
            $table->timestamp('used_at')->nullable();      // <-- TAMBAHKAN
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost_qr_tokens');
    }
};