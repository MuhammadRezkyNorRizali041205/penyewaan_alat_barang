<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alat>
 */
class AlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::factory(),
            'nama_alat' => $this->faker->word(),
            'deskripsi' => $this->faker->sentence(),
            'stok_total' => $this->faker->numberBetween(10, 100),
            'stok_tersedia' => $this->faker->numberBetween(5, 50),
            'stok_disewa' => 0,
            'harga_sewa_per_hari' => $this->faker->numberBetween(10000, 500000),
        ];
    }
}
