<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

const props = defineProps({
    penyewaan: Object,
    payment: Object,
});

const selectedMethod = ref('transfer');
const isProcessing = ref(false);

const form = useForm({
    payment_method: 'transfer',
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};

const calculateDays = () => {
    const start = new Date(props.penyewaan.tanggal_mulai);
    const end = new Date(props.penyewaan.tanggal_selesai);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays + 1;
};

const submit = () => {
    form.post(route('payment.process', props.penyewaan.id), {
        onStart: () => {
            isProcessing.value = true;
        },
        onFinish: () => {
            isProcessing.value = false;
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pembayaran Penyewaan" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <Link :href="route('penyewaan.show', penyewaan.id)" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                        ← Kembali
                    </Link>
                    <h2 class="text-3xl font-bold text-gray-900">Pembayaran Penyewaan</h2>
                    <p class="text-gray-600 mt-2">Selesaikan pembayaran untuk mengaktifkan penyewaan Anda</p>
                </div>

                <!-- Payment Status Alert -->
                <div v-if="payment && payment.status === 'paid'" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-green-800 font-semibold">✓ Pembayaran Berhasil</h3>
                            <p class="text-green-700 text-sm mt-1">Penyewaan Anda telah diaktifkan dan siap digunakan</p>
                        </div>
                        <div class="space-y-2">
                            <a :href="route('invoice.preview', penyewaan.id)" target="_blank" class="block px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 text-center">
                                Lihat Invoice
                            </a>
                            <a :href="route('invoice.download', penyewaan.id)" class="block px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                            <!-- Items -->
                            <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                                <div v-for="alat in penyewaan.alats" :key="alat.id">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 text-sm">{{ alat.nama }}</p>
                                            <p class="text-gray-500 text-xs">Qty: {{ alat.pivot.jumlah }}</p>
                                        </div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatCurrency(alat.pivot.subtotal) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rental Duration -->
                            <div class="space-y-2 mb-6 pb-6 border-b border-gray-200">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Durasi Sewa</span>
                                    <span class="font-medium text-gray-900">{{ calculateDays() }} hari</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Mulai</span>
                                    <span class="font-medium text-gray-900">{{ new Date(penyewaan.tanggal_mulai).toLocaleDateString('id-ID') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Selesai</span>
                                    <span class="font-medium text-gray-900">{{ new Date(penyewaan.tanggal_selesai).toLocaleDateString('id-ID') }}</span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                                <span>Total Pembayaran</span>
                                <span class="text-blue-600">{{ formatCurrency(penyewaan.total_harga) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Payment Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6">
                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Payment Method -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</label>

                                    <div class="space-y-3">
                                        <!-- Transfer Bank -->
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition" :class="form.payment_method === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input
                                                type="radio"
                                                v-model="form.payment_method"
                                                value="transfer"
                                                class="w-4 h-4 text-blue-600"
                                            />
                                            <div class="ms-3 flex-1">
                                                <p class="font-semibold text-gray-900">Transfer Bank</p>
                                                <p class="text-sm text-gray-600">Transfer ke rekening PT Sewa Alat Barang</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </label>

                                        <!-- Kartu Kredit -->
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition" :class="form.payment_method === 'card' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input
                                                type="radio"
                                                v-model="form.payment_method"
                                                value="card"
                                                class="w-4 h-4 text-blue-600"
                                            />
                                            <div class="ms-3 flex-1">
                                                <p class="font-semibold text-gray-900">Kartu Kredit</p>
                                                <p class="text-sm text-gray-600">Visa, Mastercard, atau kartu kredit lainnya</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </label>

                                        <!-- E-Wallet -->
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition" :class="form.payment_method === 'e-wallet' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input
                                                type="radio"
                                                v-model="form.payment_method"
                                                value="e-wallet"
                                                class="w-4 h-4 text-blue-600"
                                            />
                                            <div class="ms-3 flex-1">
                                                <p class="font-semibold text-gray-900">E-Wallet</p>
                                                <p class="text-sm text-gray-600">GoPay, OVO, DANA, atau LinkAja</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </label>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-900">
                                        ✓ Pembayaran Anda akan diproses segera<br>
                                        ✓ Penyewaan akan aktif setelah pembayaran berhasil<br>
                                        ✓ Garansi uang kembali 100% jika ada masalah
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <PrimaryButton
                                    type="submit"
                                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition"
                                    :disabled="isProcessing || (payment && payment.status === 'paid')"
                                >
                                    <span v-if="isProcessing" class="inline-flex items-center">
                                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                        Memproses...
                                    </span>
                                    <span v-else>
                                        Bayar Sekarang - {{ formatCurrency(penyewaan.total_harga) }}
                                    </span>
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
