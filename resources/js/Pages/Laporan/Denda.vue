<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({ dendaByMonth: Array });
</script>

<template>
    <Head title="Laporan Denda" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Laporan Denda</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Denda per Bulan</h3>

                        <table class="w-full table-auto">
                            <thead>
                                <tr class="text-left text-sm text-gray-600">
                                    <th>Bulan</th>
                                    <th>Total Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in props.dendaByMonth" :key="row.month" class="border-t">
                                    <td class="py-2">{{ row.month }}</td>
                                    <td class="py-2">Rp {{ row.total }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
