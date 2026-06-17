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
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('token');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost_qr_tokens');
    }
};