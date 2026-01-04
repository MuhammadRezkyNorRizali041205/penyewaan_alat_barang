<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    penyewaan: {
        type: Object,
        required: true,
    },
    payment: {
        type: Object,
        required: true,
    },
    invoiceNumber: {
        type: String,
        required: true,
    },
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

// Auto redirect after 5 seconds
onMounted(() => {
    setTimeout(() => {
        window.location.href = '/dashboard';
    }, 5000);
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Success Container -->
            <div class="bg-white rounded-lg shadow-2xl p-8 text-center animate-fade-in">
                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="animate-bounce">
                        <svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mb-6">Rental Anda telah disetujui dan siap digunakan</p>

                <!-- Transaction Details -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Invoice</span>
                        <span class="font-semibold text-gray-900">{{ invoiceNumber }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Transaksi</span>
                        <span class="font-mono text-sm text-gray-900">{{ payment.transaction_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Pembayaran</span>
                        <span class="font-bold text-green-600">{{ formatCurrency(payment.amount) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-gray-600">Metode</span>
                        <span class="font-semibold text-gray-900 capitalize">{{ payment.payment_method }}</span>
                    </div>
                </div>

                <!-- Rental Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-semibold text-blue-900 mb-2">Detail Penyewaan</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="text-blue-700">Alat:</span> <span class="text-blue-900 font-medium">{{ penyewaan.alats[0]?.nama_alat }}</span></p>
                        <p><span class="text-blue-700">Tanggal:</span> <span class="text-blue-900 font-medium">{{ new Date(penyewaan.tanggal_mulai).toLocaleDateString('id-ID') }} s/d {{ new Date(penyewaan.tanggal_selesai).toLocaleDateString('id-ID') }}</span></p>
                    </div>
                </div>

                <!-- Auto Redirect Notice -->
                <div class="text-sm text-gray-500 mb-6 animate-pulse">
                    ⏱️ Mengalihkan ke dashboard dalam beberapa detik...
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a :href="`/invoice/${penyewaan.id}/download`" class="block w-full bg-blue-600 text-white rounded-lg py-3 font-semibold hover:bg-blue-700 transition">
                        📄 Download Invoice
                    </a>
                    <Link href="/dashboard" class="block w-full bg-gray-200 text-gray-900 rounded-lg py-3 font-semibold hover:bg-gray-300 transition">
                        ← Kembali ke Dashboard
                    </Link>
                </div>
            </div>

            <!-- Footer Message -->
            <p class="text-center text-gray-500 text-sm mt-6">
                Terima kasih telah menggunakan layanan kami
            </p>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
