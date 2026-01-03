<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
const props = defineProps({ totalPenyewaan: Number, totalPengembalian: Number, totalDenda: Number });
</script>

<template>
    <Head title="Laporan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Laporan</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="p-4 border rounded">
                                <div class="text-sm text-gray-500">Total Penyewaan</div>
                                <div class="text-2xl font-semibold">{{ props.totalPenyewaan }}</div>
                                <div class="mt-2"><Link href="/laporan/penyewaan" class="text-indigo-600">Lihat detail</Link></div>
                            </div>

                            <div class="p-4 border rounded">
                                <div class="text-sm text-gray-500">Total Pengembalian</div>
                                <div class="text-2xl font-semibold">{{ props.totalPengembalian }}</div>
                            </div>

                            <div class="p-4 border rounded">
                                <div class="text-sm text-gray-500">Total Denda</div>
                                <div class="text-2xl font-semibold">Rp {{ props.totalDenda }}</div>
                                <div class="mt-2"><Link href="/laporan/denda" class="text-indigo-600">Lihat detail</Link></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
