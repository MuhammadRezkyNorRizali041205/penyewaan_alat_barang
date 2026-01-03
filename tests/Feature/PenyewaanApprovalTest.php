<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PenyewaanApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $penyewa;

    protected User $admin;

    protected Alat $alat;

    protected function setUp(): void
    {
        parent::setUp();

        // Create regular penyewa with 'user' role
        $this->penyewa = User::factory()->create(['id' => 2, 'role' => 'user']);

        // Create admin user with 'admin' role
        $this->admin = User::factory()->create(['id' => 1, 'role' => 'admin']);

        // Create kategori and alat
        $kategori = Kategori::factory()->create();
        $this->alat = Alat::factory()
            ->for($kategori)
            ->state([
                'stok_total' => 100,
                'stok_tersedia' => 50,
                'stok_disewa' => 0,
                'harga_sewa_per_hari' => 100000,
            ])
            ->create();
    }

    #[Test]
    public function user_can_create_rental_request(): void
    {
        $response = $this->actingAs($this->penyewa)
            ->postJson('/api/penyewaan', [
                'tanggal_mulai' => now()->addDay()->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(5)->format('Y-m-d'),
                'items' => [
                    [
                        'alat_id' => $this->alat->id,
                        'jumlah' => 2,
                    ],
                ],
                'catatan' => 'Test rental',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('penyewaans', [
            'penyewa_id' => $this->penyewa->id,
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function admin_can_approve_rental(): void
    {
        $penyewaan = Penyewaan::factory()
            ->for($this->penyewa, 'penyewa')
            ->create();

        $penyewaan->alats()->attach($this->alat->id, [
            'jumlah' => 5,
            'harga_satuan' => 100000,
            'subtotal' => 500000,
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson("/api/penyewaan/{$penyewaan->id}/approve");

        $response->assertOk();
        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'approved',
        ]);
    }

    #[Test]
    public function admin_can_reject_rental(): void
    {
        $penyewaan = Penyewaan::factory()
            ->for($this->penyewa, 'penyewa')
            ->create();

        $response = $this->actingAs($this->admin)
            ->postJson("/api/penyewaan/{$penyewaan->id}/reject", [
                'alasan_penolakan' => 'Stok sedang tidak tersedia untuk periode ini',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'rejected',
        ]);
    }

    #[Test]
    public function penyewa_cannot_approve_rental(): void
    {
        $penyewaan = Penyewaan::factory()
            ->for($this->penyewa, 'penyewa')
            ->create();

        $response = $this->actingAs($this->penyewa)
            ->postJson("/api/penyewaan/{$penyewaan->id}/approve");

        $response->assertForbidden();
    }

    #[Test]
    public function stock_is_reserved_on_approval(): void
    {
        $penyewaan = Penyewaan::factory()
            ->for($this->penyewa, 'penyewa')
            ->create();

        $penyewaan->alats()->attach($this->alat->id, [
            'jumlah' => 10,
            'harga_satuan' => 100000,
            'subtotal' => 1000000,
        ]);

        $this->actingAs($this->admin)
            ->postJson("/api/penyewaan/{$penyewaan->id}/approve");

        $this->alat->refresh();
        $this->assertEquals(40, $this->alat->stok_tersedia);
        $this->assertEquals(10, $this->alat->stok_disewa);
    }
}
