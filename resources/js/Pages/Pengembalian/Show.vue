<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ penyewaan: Object });

function submitReturn() {
    const payload = {
        tanggal_pengembalian: document.getElementById('tanggal_pengembalian').value,
        status_pengembalian: document.getElementById('status_pengembalian').value,
    };

    router.post(route('pengembalian.process', props.penyewaan.id), payload);
}
</script>

<template>
    <Head :title="`Pengembalian #${props.penyewaan.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Pengembalian</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Link href="/pengembalian">← Kembali ke Daftar Pengembalian</Link>

                        <h3 class="text-lg font-semibold mb-4 mt-4">Detail Penyewaan #{{ props.penyewaan.id }}</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm text-gray-600">Penyewa</label>
                                <p class="text-gray-900 font-medium">{{ props.penyewaan.penyewa.name }}</p>
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Periode</label>
                                <p class="text-gray-900">{{ new Date(props.penyewaan.tanggal_mulai).toLocaleDateString() }} - {{ new Date(props.penyewaan.tanggal_selesai).toLocaleDateString() }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-semibold">Item</h4>
                            <div v-if="props.penyewaan.alats.length === 0" class="text-gray-500">Tidak ada item.</div>
                            <div v-else>
                                <div v-for="alat in props.penyewaan.alats" :key="alat.id" class="border-b py-2">
                                    <div class="flex justify-between">
                                        <div>{{ alat.nama }}</div>
                                        <div class="text-sm text-gray-600">x{{ alat.pivot.jumlah }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-semibold">Proses Pengembalian</h4>
                            <div class="mt-2 space-y-2">
                                <div>
                                    <label class="block text-sm text-gray-600">Tanggal Pengembalian</label>
                                    <input id="tanggal_pengembalian" type="date" class="mt-1 block w-full border rounded px-3 py-2" :value="new Date().toISOString().substr(0,10)" />
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-600">Status Pengembalian</label>
                                    <select id="status_pengembalian" class="mt-1 block w-full border rounded px-3 py-2">
                                        <option value="lengkap">Lengkap</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>

                                <div>
                                    <button @click.prevent="submitReturn" class="px-4 py-2 bg-green-600 text-white rounded">Proses Pengembalian</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
