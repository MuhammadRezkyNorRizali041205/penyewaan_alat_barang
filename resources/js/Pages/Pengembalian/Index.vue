<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({ penyewaans: Object });
</script>

<template>
    <Head title="Pengembalian" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Pengembalian</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Daftar Penyewaan (Siap Dikembalikan)</h3>

                        <div v-if="!props.penyewaans.data.length" class="text-center py-12">
                            <p class="text-gray-500">Tidak ada penyewaan yang perlu pengembalian.</p>
                        </div>

                        <table v-else class="w-full table-auto">
                            <thead>
                                <tr class="text-left text-sm text-gray-600">
                                    <th>#</th>
                                    <th>Penyewa</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="penyewaan in props.penyewaans.data" :key="penyewaan.id" class="border-t">
                                    <td class="py-3">#{{ penyewaan.id }}</td>
                                    <td class="py-3">{{ penyewaan.penyewa.name }}</td>
                                    <td class="py-3">{{ new Date(penyewaan.tanggal_mulai).toLocaleDateString() }} - {{ new Date(penyewaan.tanggal_selesai).toLocaleDateString() }}</td>
                                    <td class="py-3">{{ penyewaan.status }}</td>
                                    <td class="py-3">
                                        <Link :href="route('pengembalian.show', penyewaan.id)" class="text-indigo-600">Detail & Proses</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="props.penyewaans.links" class="mt-6 flex justify-center">
                            <nav>
                                <ul class="flex gap-2">
                                    <li v-for="link in props.penyewaans.links" :key="link.label">
                                        <Link v-if="link.url" :href="link.url" v-html="link.label" class="px-3 py-1 border rounded" />
                                        <span v-else v-html="link.label" class="px-3 py-1 border rounded text-gray-500" />
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
