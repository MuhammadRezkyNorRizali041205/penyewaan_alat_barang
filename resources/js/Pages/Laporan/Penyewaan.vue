<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({ stats: Array });
</script>

<template>
    <Head title="Laporan Penyewaan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Laporan Penyewaan</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Statistik Penyewaan (per status)</h3>

                        <table class="w-full table-auto">
                            <thead>
                                <tr class="text-left text-sm text-gray-600">
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in props.stats" :key="row.status" class="border-t">
                                    <td class="py-2">{{ row.status }}</td>
                                    <td class="py-2">{{ row.total }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
