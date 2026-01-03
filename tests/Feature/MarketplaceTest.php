<?php

namespace Tests\Feature;

use App\Models\Alat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_index_and_show()
    {
        $alat = Alat::factory()->create([
            'nama_alat' => 'Kamera DSLR',
            'harga_sewa_per_hari' => 150000,
            'stok_tersedia' => 5,
        ]);

        $response = $this->get('/alat');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Marketplace/Index')
            ->has('alats.data', 1)
            ->where('alats.data.0.nama', 'Kamera DSLR')
        );

        $response = $this->get('/alat/'.$alat->id);
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Marketplace/Show')
            ->where('alat.nama', 'Kamera DSLR')
            ->where('alat.harga_sewa', 150000)
            ->where('alat.stok', 5)
        );
    }
}
