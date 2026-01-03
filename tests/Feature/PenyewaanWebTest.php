<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PenyewaanWebTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $penyewa;

    protected Penyewaan $penyewaan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['id' => 1, 'role' => 'admin']);
        $this->penyewa = User::factory()->create(['id' => 2, 'role' => 'user']);

        // Create kategori and alat
        $kategori = Kategori::factory()->create();
        $alat = Alat::factory()->create([
            'kategori_id' => $kategori->id,
            'stok_total' => 10,
            'stok_tersedia' => 10,
            'stok_disewa' => 0,
        ]);

        // Create rental
        $this->penyewaan = Penyewaan::factory()->create([
            'penyewa_id' => $this->penyewa->id,
            'status' => 'pending',
        ]);

        $this->penyewaan->alats()->attach($alat, [
            'jumlah' => 2,
            'harga_satuan' => 50000,
            'subtotal' => 100000,
        ]);
    }

    #[Test]
    public function user_can_view_rental_list(): void
    {
        $response = $this->actingAs($this->penyewa)
            ->get('/penyewaan');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Penyewaan/Index')
            ->has('penyewaans.data', 1)
        );
    }

    #[Test]
    public function user_can_view_rental_detail(): void
    {
        $response = $this->actingAs($this->penyewa)
            ->get("/penyewaan/{$this->penyewaan->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Penyewaan/Show')
            ->where('penyewaan.id', $this->penyewaan->id)
        );
    }

    #[Test]
    public function admin_sees_approve_button(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/penyewaan/{$this->penyewaan->id}");

        $response->assertInertia(fn ($page) => $page
            ->where('canApprove', true)
            ->where('canReject', true)
        );
    }

    #[Test]
    public function penyewa_does_not_see_approve_button(): void
    {
        $response = $this->actingAs($this->penyewa)
            ->get("/penyewaan/{$this->penyewaan->id}");

        $response->assertInertia(fn ($page) => $page
            ->where('canApprove', false)
            ->where('canReject', false)
        );
    }

    #[Test]
    public function admin_can_approve_rental_via_web(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/penyewaan/{$this->penyewaan->id}/approve", []);

        $response->assertRedirect("/penyewaan/{$this->penyewaan->id}");

        $this->assertTrue($this->penyewaan->fresh()->isApproved());
    }

    #[Test]
    public function admin_can_reject_rental_via_web(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/penyewaan/{$this->penyewaan->id}/reject", [
                'alasan_penolakan' => 'Tidak tersedia pada tanggal yang diminta',
            ]);

        $response->assertRedirect("/penyewaan/{$this->penyewaan->id}");

        $this->assertTrue($this->penyewaan->fresh()->isRejected());
    }

    #[Test]
    public function penyewa_cannot_approve_rental(): void
    {
        $response = $this->actingAs($this->penyewa)
            ->post("/penyewaan/{$this->penyewaan->id}/approve", []);

        $response->assertForbidden();
    }

    #[Test]
    public function unauthenticated_user_cannot_access_rentals(): void
    {
        $response = $this->get('/penyewaan');

        $response->assertRedirect('/login');
    }
}
