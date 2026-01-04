<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    payments: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'paid':
            return 'Terbayar';
        case 'pending':
            return 'Menunggu';
        case 'failed':
            return 'Gagal';
        default:
            return status;
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Riwayat Pembayaran" />

        <div class="py-12">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Riwayat Pembayaran</h2>
                    <p class="text-gray-600 mt-2">Lihat semua riwayat pembayaran penyewaan Anda</p>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div v-if="payments.data.length === 0" class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada pembayaran</h3>
                        <p class="mt-1 text-gray-600">Lakukan pembayaran untuk penyewaan Anda yang sudah disetujui</p>
                        <Link :href="route('penyewaan.index')" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Lihat Penyewaan
                        </Link>
                    </div>

                    <table v-else class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-mono text-sm font-semibold text-gray-900">{{ payment.transaction_id || `PAY-${payment.id}` }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">{{ formatCurrency(payment.amount) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-3 py-1 text-sm font-semibold rounded-full', getStatusBadgeClass(payment.status)]">
                                        {{ getStatusLabel(payment.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600 capitalize">{{ payment.payment_method || '-' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600">{{ formatDate(payment.paid_at || payment.created_at) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div v-if="payment.status === 'paid'" class="flex gap-1">
                                        <a :href="route('invoice.preview', payment.penyewaan_id)" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                            Lihat
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a :href="route('invoice.download', payment.penyewaan_id)" class="text-green-600 hover:text-green-800 text-sm font-semibold">
                                            Download
                                        </a>
                                    </div>
                                    <span v-else class="text-gray-400 text-sm">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link :href="route('penyewaan.show', payment.penyewaan_id)" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        Detail
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="payments.links" class="mt-6 flex justify-center gap-2">
                    <Link
                        v-for="link in payments.links"
                        :key="link.label"
                        :href="link.url"
                        :class="[
                            'px-4 py-2 rounded-lg font-semibold transition',
                            link.active
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
