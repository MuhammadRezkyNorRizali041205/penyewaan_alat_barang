<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penyewaan>
 */
class PenyewaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalMulai = now()->addDays($this->faker->numberBetween(1, 10));
        $tanggalSelesai = $tanggalMulai->addDays($this->faker->numberBetween(1, 20));

        return [
            'penyewa_id' => User::factory(),
            'petugas_id' => null,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'status' => 'pending',
            'total_harga' => null,
            'catatan' => $this->faker->sentence(),
            'tanggal_approval' => null,
            'alasan_penolakan' => null,
        ];
    }

    /**
     * Indicate that the rental has been approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'petugas_id' => User::factory(),
            'tanggal_approval' => now(),
        ]);
    }

    /**
     * Indicate that the rental has been rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'petugas_id' => User::factory(),
            'tanggal_approval' => now(),
            'alasan_penolakan' => 'Stok tidak tersedia',
        ]);
    }
}
