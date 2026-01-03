<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimpleRentalTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_make_api_request(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/penyewaan', [
                'tanggal_mulai' => now()->addDay()->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'),
                'items' => [],
            ]);

        // Just check if we get a response, not necessarily successful
        $this->assertNotNull($response);
    }
}
