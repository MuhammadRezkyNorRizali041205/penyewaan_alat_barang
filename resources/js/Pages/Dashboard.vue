<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    auth: Object,
    stats: Object,
    recentActivity: Array,
    needsPayment: Array,
});

const getStatusColor = (status) => {
    switch (status) {
        case 'approved':
        case 'approved_unpaid':
            return 'bg-green-100 text-green-800';
        case 'paid':
            return 'bg-blue-100 text-blue-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        case 'returned':
            return 'bg-purple-100 text-purple-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusLabel = (status) => {
    const labels = {
        approved: 'Disetujui',
        approved_unpaid: 'Menunggu Pembayaran',
        paid: 'Aktif',
        pending: 'Menunggu',
        rejected: 'Ditolak',
        returned: 'Selesai',
    };
    return labels[status] || status;
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Header Section -->
        <template #header>
            <div>
                <h2 class="text-3xl font-bold leading-tight text-gray-900">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Selamat datang kembali, <span class="font-semibold">{{ auth.user.name }}</span>
                </p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Penyewaan Card -->
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Penyewaan</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalPenyewaan }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 20H5a2 2 0 01-2-2V9a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2m0-5V9m0 0a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2h-2a2 2 0 01-2-2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-4">Semua penyewaan sepanjang masa</p>
                    </div>

                    <!-- Active Penyewaan Card -->
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Penyewaan Aktif</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.activePenyewaan }}</p>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-4">Sedang berjalan saat ini</p>
                    </div>

                    <!-- Pending Approval Card -->
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Menunggu Persetujuan</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.pendingApproval }}</p>
                            </div>
                            <div class="bg-yellow-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-4">Menunggu review admin</p>
                    </div>

                    <!-- Denda Card -->
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Denda</p>
                                <p class="text-2xl font-bold text-gray-900 mt-2">{{ formatCurrency(stats.totalDenda) }}</p>
                            </div>
                            <div class="bg-red-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-4">Belum dibayarkan</p>
                    </div>
                </div>

                <!-- Pending Payments Alert (if any) -->
                <div v-if="needsPayment && needsPayment.length > 0" class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-orange-900">⚠️ Menunggu Pembayaran</h3>
                            <p class="text-orange-800 mt-1">{{ needsPayment.length }} penyewaan menunggu pembayaran Anda</p>
                        </div>
                        <div class="space-x-2">
                            <Link v-for="penyewaan in needsPayment.slice(0, 2)" :key="penyewaan.id" :href="route('payment.show', penyewaan.id)" class="inline-block px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold text-sm">
                                Bayar Sekarang
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Quick Actions -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                            <div class="space-y-3">
                                <Link
                                    href="/penyewaan"
                                    class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6" />
                                    </svg>
                                    Buat Penyewaan
                                </Link>

                                <Link
                                    href="/alat"
                                    class="flex items-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Jelajahi Marketplace
                                </Link>

                                <Link
                                    href="/pengembalian"
                                    class="flex items-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1010 0A5 5 0 007 12z" />
                                    </svg>
                                    Proses Pengembalian
                                </Link>

                                <Link
                                    href="/riwayat-pembayaran"
                                    class="flex items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Riwayat Pembayaran
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Table -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                                <Link href="/penyewaan" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    Lihat Semua →
                                </Link>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-gray-200">
                                        <tr>
                                            <th class="text-left py-2 text-gray-600 font-semibold">Item</th>
                                            <th class="text-left py-2 text-gray-600 font-semibold">Status</th>
                                            <th class="text-left py-2 text-gray-600 font-semibold">Harga</th>
                                            <th class="text-left py-2 text-gray-600 font-semibold">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="activity in recentActivity" :key="activity.id" class="hover:bg-gray-50 transition">
                                            <td class="py-3">
                                                <p class="font-medium text-gray-900">{{ activity.items }}</p>
                                            </td>
                                            <td class="py-3">
                                                <span :class="['px-3 py-1 text-xs font-semibold rounded-full', getStatusColor(activity.status)]">
                                                    {{ getStatusLabel(activity.status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-semibold text-gray-900">{{ formatCurrency(activity.amount) }}</td>
                                            <td class="py-3 text-gray-600">{{ activity.date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Banner -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Butuh bantuan?</h3>
                            <p class="text-blue-100 mt-1">Hubungi tim support kami untuk pertanyaan atau masalah apapun</p>
                        </div>
                        <a
                            href="https://wa.me/6287836714695?text=Halo%20saya%20butuh%20bantuan%20dengan%20sistem%20penyewaan%20alat"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-6 py-2 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition flex items-center gap-2"
                        >
                            💬 Hubungi Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
