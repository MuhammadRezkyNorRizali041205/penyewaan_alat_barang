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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pengembalian');
            $table->integer('hari_keterlambatan')->default(0);
            $table->decimal('denda', 10, 2)->default(0);
            $table->enum('status_pengembalian', ['lengkap', 'rusak', 'hilang'])->default('lengkap');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('penyewaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
