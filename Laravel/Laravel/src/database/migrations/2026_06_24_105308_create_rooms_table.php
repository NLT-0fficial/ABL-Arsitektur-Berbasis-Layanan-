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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode unik kamar, contoh: A-101');
            $table->enum('category', ['A', 'B', 'C'])->comment('Kategori kamar');
            $table->unsignedInteger('floor')->nullable()->comment('Lantai kamar');
            $table->decimal('rent_price', 10, 2)->default(0)->comment('Harga sewa per bulan');
            $table->boolean('is_occupied')->default(false)->comment('Status terisi/kosong');
            $table->string('tenant_name')->nullable()->comment('Nama penghuni jika kamar terisi');
            $table->string('tenant_phone', 20)->nullable()->comment('No HP penghuni jika kamar terisi');
            $table->date('occupied_since')->nullable()->comment('Tanggal mulai menghuni');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('is_occupied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};