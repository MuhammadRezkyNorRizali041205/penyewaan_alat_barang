<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $petugas = User::factory()->create([
            'name' => 'Petugas',
            'email' => 'petugas@example.com',
        ]);

        $penyewa = User::factory()->create([
            'name' => 'Penyewa',
            'email' => 'penyewa@example.com',
        ]);

        // Create categories
        $categoryTools = Kategori::factory()->create([
            'nama_kategori' => 'Alat Pertukangan',
            'deskripsi' => 'Alat-alat untuk kebutuhan pertukangan',
        ]);

        $categoryElectronics = Kategori::factory()->create([
            'nama_kategori' => 'Peralatan Elektronik',
            'deskripsi' => 'Peralatan elektronik untuk berbagai kebutuhan',
        ]);

        // Create sample equipment
        Alat::factory(5)->for($categoryTools)->create();
        Alat::factory(5)->for($categoryElectronics)->create();
    }
}
