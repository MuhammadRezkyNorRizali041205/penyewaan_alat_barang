<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Penyewaan #{{ penyewaan.id }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
          <Link
            href="/penyewaan"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
          >
            ← Kembali
          </Link>
        </div>

        <!-- Status Alert -->
        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Main Info -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
              <h3 class="text-lg font-semibold mb-4">Informasi Penyewaan</h3>

              <div class="space-y-4">
                <div>
                  <label class="text-sm text-gray-600">Status</label>
                  <p :class="statusBadgeClass(penyewaan.status)" class="px-3 py-1 inline-block text-sm font-semibold rounded">
                    {{ penyewaan.status }}
                  </p>
                </div>

                <div>
                  <label class="text-sm text-gray-600">Penyewa</label>
                  <p class="text-gray-900 font-medium">{{ penyewaan.penyewa.name }}</p>
                </div>

                <div>
                  <label class="text-sm text-gray-600">Tanggal Mulai</label>
                  <p class="text-gray-900">{{ formatDate(penyewaan.tanggal_mulai) }}</p>
                </div>

                <div>
                  <label class="text-sm text-gray-600">Tanggal Selesai</label>
                  <p class="text-gray-900">{{ formatDate(penyewaan.tanggal_selesai) }}</p>
                </div>

                <div>
                  <label class="text-sm text-gray-600">Catatan</label>
                  <p class="text-gray-900">{{ penyewaan.catatan || '-' }}</p>
                </div>

                <div>
                  <label class="text-sm text-gray-600">Dibuat</label>
                  <p class="text-gray-900">{{ formatDateTime(penyewaan.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Items -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
              <h3 class="text-lg font-semibold mb-4">Alat yang Disewa</h3>

              <div v-if="penyewaan.alats.length === 0" class="text-center py-8 text-gray-500">
                Tidak ada alat
              </div>

              <div v-else class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                  <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Barang</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Kategori</th>
                      <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Jumlah</th>
                      <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Harga Satuan</th>
                      <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="alat in penyewaan.alats" :key="alat.id" class="border-b hover:bg-gray-50">
                      <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ alat.nama }}</td>
                      <td class="px-4 py-3 text-sm text-gray-600">{{ alat.kategori?.nama_kategori || '-' }}</td>
                      <td class="px-4 py-3 text-sm text-center text-gray-600">{{ alat.pivot.jumlah }}</td>
                      <td class="px-4 py-3 text-sm text-right text-gray-600">Rp {{ formatMoney(alat.pivot.harga_satuan) }}</td>
                      <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900">Rp {{ formatMoney(alat.pivot.subtotal) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div v-if="canApprove || canReject" class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold mb-4">Aksi</h3>

            <div class="space-y-4">
              <form v-if="canApprove" @submit.prevent="approve" class="inline">
                <button
                  type="submit"
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                >
                  Setujui Penyewaan
                </button>
              </form>

              <form v-if="canReject" @submit.prevent="submitReject" class="inline">
                <button
                  type="submit"
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                >
                  Tolak Penyewaan
                </button>
              </form>
            </div>

            <!-- Reject Modal -->
            <div v-if="showRejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Alasan Penolakan</h3>

                <textarea
                  v-model="rejectReason"
                  class="w-full border rounded-md p-2 mb-4"
                  rows="4"
                  placeholder="Masukkan alasan penolakan..."
                  @keydown.escape="showRejectModal = false"
                />

                <div class="flex justify-end space-x-2">
                  <button
                    type="button"
                    @click="showRejectModal = false"
                    class="px-4 py-2 text-gray-600 border rounded-md hover:bg-gray-50"
                  >
                    Batal
                  </button>
                  <button
                    type="button"
                    @click="submitRejectModal"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                  >
                    Tolak
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Return Info -->
        <div v-if="penyewaan.pengembalian" class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold mb-4">Informasi Pengembalian</h3>

            <div class="space-y-2">
              <div>
                <label class="text-sm text-gray-600">Tanggal Pengembalian</label>
                <p class="text-gray-900">{{ formatDate(penyewaan.pengembalian.tanggal_pengembalian) }}</p>
              </div>
              <div v-if="penyewaan.pengembalian.denda">
                <label class="text-sm text-gray-600">Denda</label>
                <p class="font-semibold text-red-600">Rp {{ formatMoney(penyewaan.pengembalian.denda) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  penyewaan: {
    type: Object,
    required: true,
  },
  canApprove: {
    type: Boolean,
    default: false,
  },
  canReject: {
    type: Boolean,
    default: false,
  },
});

const showRejectModal = ref(false);
const rejectReason = ref('');

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

const formatDateTime = (datetime) => {
  return new Date(datetime).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatMoney = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
  }).format(amount);
};

const statusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    returned: 'bg-blue-100 text-blue-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const approve = () => {
  router.post(route('penyewaan.approve', penyewaan.id));
};

const submitReject = () => {
  showRejectModal.value = true;
};

const submitRejectModal = () => {
  router.post(route('penyewaan.reject', penyewaan.id), {
    alasan: rejectReason.value,
  });
};
</script>
