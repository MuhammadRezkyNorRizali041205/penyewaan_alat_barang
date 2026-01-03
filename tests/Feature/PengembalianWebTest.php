<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengembalianWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengembalian_index_and_show_and_process()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $penyewaan = Penyewaan::factory()->create(['status' => 'approved', 'penyewa_id' => $user->id]);
        $alat = Alat::factory()->create();
        $penyewaan->alats()->attach($alat->id, ['jumlah' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000]);

        $response = $this->get('/pengembalian');
        $response->assertStatus(200);

        $response = $this->get("/pengembalian/{$penyewaan->id}");
        $response->assertStatus(200);

        $response = $this->postJson("/pengembalian/{$penyewaan->id}/process", [
            'tanggal_pengembalian' => now()->toDateString(),
            'status_pengembalian' => 'lengkap',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pengembalians', ['penyewaan_id' => $penyewaan->id]);
    }
}
