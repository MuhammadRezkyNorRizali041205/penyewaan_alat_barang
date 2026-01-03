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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); // e.g., 'approve_penyewaan', 'reject_penyewaan', 'reset_password'
            $table->text('description'); // Human-readable description
            $table->string('subject_type')->nullable(); // e.g., 'Penyewaan', 'User'
            $table->unsignedBigInteger('subject_id')->nullable(); // ID of the related entity
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
