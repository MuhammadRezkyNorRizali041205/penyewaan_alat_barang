<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                        <div class="space-y-2">
                            <Link
                                href="/penyewaan"
                                class="block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Lihat Daftar Penyewaan
                            </Link>

                            <Link
                                href="/pengembalian"
                                class="block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                            >
                                Proses Pengembalian
                            </Link>

                            <Link
                                href="/laporan"
                                class="block px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800"
                            >
                                Laporan & Statistik
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
