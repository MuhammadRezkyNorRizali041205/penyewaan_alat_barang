<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF verification for all tests
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        // Ensure a minimal Vite manifest is available during tests to avoid
        // view rendering failures when assets haven't been built.
        $manifestPath = public_path('build/manifest.json');

        if (! is_file($manifestPath)) {
            if (! is_dir(dirname($manifestPath))) {
                mkdir(dirname($manifestPath), 0755, true);
            }

            // Minimal manifest entries for pages used in tests to avoid Vite errors
            $manifest = [
                'resources/js/app.js' => [
                    'file' => 'assets/app.js',
                    'isEntry' => true,
                    'css' => ['assets/app.css'],
                ],
                'resources/js/Pages/Dashboard.vue' => [
                    'file' => 'assets/Dashboard.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Dashboard.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Penyewaan/Index.vue' => [
                    'file' => 'assets/Index.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Penyewaan/Index.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Penyewaan/Show.vue' => [
                    'file' => 'assets/Show.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Penyewaan/Show.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Penyewaan/Create.vue' => [
                    'file' => 'assets/Create.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Penyewaan/Create.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Pengembalian/Index.vue' => [
                    'file' => 'assets/PengembalianIndex.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Pengembalian/Index.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Pengembalian/Show.vue' => [
                    'file' => 'assets/PengembalianShow.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Pengembalian/Show.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Laporan/Index.vue' => [
                    'file' => 'assets/LaporanIndex.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Laporan/Index.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Laporan/Penyewaan.vue' => [
                    'file' => 'assets/LaporanPenyewaan.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Laporan/Penyewaan.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Laporan/Denda.vue' => [
                    'file' => 'assets/LaporanDenda.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Laporan/Denda.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                // Marketplace pages used by tests
                'resources/js/Pages/Marketplace/Index.vue' => [
                    'file' => 'assets/MarketplaceIndex.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Marketplace/Index.vue',
                    'imports' => ['resources/js/app.js'],
                ],
                'resources/js/Pages/Marketplace/Show.vue' => [
                    'file' => 'assets/MarketplaceShow.js',
                    'isDynamicEntry' => true,
                    'src' => 'resources/js/Pages/Marketplace/Show.vue',
                    'imports' => ['resources/js/app.js'],
                ],
            ];

            file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
        }

        // Use array session driver during tests to avoid database session access
        config(['session.driver' => 'array']);
    }
}
