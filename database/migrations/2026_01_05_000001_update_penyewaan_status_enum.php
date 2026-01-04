<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Change enum to include approved_unpaid
            $table->enum('status', ['pending', 'approved', 'approved_unpaid', 'paid', 'rejected', 'returned', 'cancelled'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'cancelled'])->change();
        });
    }
};
