<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const route = window.route;

const props = defineProps({
  alat: {
    type: Object,
    required: true,
  },
});

const showModal = ref(false);
const isSubmitting = ref(false);
const formData = ref({
  tanggal_mulai: '',
  tanggal_selesai: '',
  jumlah: 1,
  catatan: '',
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(value || 0);
};

const isOutOfStock = () => {
  return (props.alat.stok_tersedia ?? props.alat.stok) <= 0;
};

const rentalDays = computed(() => {
  if (!formData.value.tanggal_mulai || !formData.value.tanggal_selesai) return 0;
  const start = new Date(formData.value.tanggal_mulai);
  const end = new Date(formData.value.tanggal_selesai);
  const diffTime = Math.abs(end - start);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return Math.max(1, diffDays);
});

const totalPrice = computed(() => {
  if (!formData.value.jumlah) return 0;
  const pricePerDay = props.alat.harga_sewa_per_hari ?? props.alat.harga_sewa;
  return pricePerDay * rentalDays.value * formData.value.jumlah;
});

const canSubmit = computed(() => {
  return formData.value.tanggal_mulai &&
         formData.value.tanggal_selesai &&
         formData.value.jumlah > 0 &&
         formData.value.jumlah <= (props.alat.stok_tersedia ?? props.alat.stok) &&
         !isSubmitting.value;
});

const handleSewaClick = () => {
  // If out of stock, do nothing
  if (isOutOfStock()) {
    return;
  }

  // Navigate to penyewaan create page with alat_id
  router.visit(route('penyewaan.create', { alat_id: props.alat.id }));
};

const submitForm = () => {
  if (!canSubmit.value) return;

  isSubmitting.value = true;

  router.post(
    '/penyewaan',
    {
      alat_id: props.alat.id,
      tanggal_mulai: formData.value.tanggal_mulai,
      tanggal_selesai: formData.value.tanggal_selesai,
      jumlah: parseInt(formData.value.jumlah),
      catatan: formData.value.catatan || null,
    },
    {
      onSuccess: () => {
        showModal.value = false;
        // Reset form
        formData.value = {
          tanggal_mulai: '',
          tanggal_selesai: '',
          jumlah: 1,
          catatan: '',
        };
        isSubmitting.value = false;
        // Redirect to penyewaan page
        router.visit('/penyewaan');
      },
      onError: (errors) => {
        isSubmitting.value = false;
        // Check if it's an authentication error
        if (errors.message && errors.message.includes('Unauthenticated')) {
          router.visit('/login');
          return;
        }
        console.error('Error:', errors);
        alert('Gagal membuat penyewaan: ' + Object.values(errors).join(', '));
      },
    }
  );
};

const closeModal = () => {
  showModal.value = false;
  // Reset form
  formData.value = {
    tanggal_mulai: '',
    tanggal_selesai: '',
    jumlah: 1,
    catatan: '',
  };
};
</script>

<template>
  <div :class="[
    'group relative bg-white rounded-lg shadow-md transition-all duration-300',
    'hover:shadow-xl hover:scale-105 flex flex-col h-full',
    isOutOfStock() ? 'opacity-60' : ''
  ]">
    <!-- Out of Stock Badge -->
    <div v-if="isOutOfStock()" class="absolute top-2 right-2 z-10 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
      Habis
    </div>

    <!-- Image Container -->
    <div class="relative w-full h-52 bg-gradient-to-br from-gray-200 to-gray-100 overflow-hidden rounded-t-lg">
      <img
        v-if="props.alat.gambar_url"
        :src="props.alat.gambar_url"
        :alt="props.alat.nama_alat"
        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
        loading="lazy"
      />
      <div v-else class="flex items-center justify-center w-full h-full text-gray-400">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
      </div>

      <!-- Category Badge -->
      <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
        {{ props.alat.kategori?.nama_kategori ?? 'Umum' }}
      </div>
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col h-full">
      <!-- Title -->
      <h3 class="font-semibold text-gray-900 truncate text-sm">
        {{ props.alat.nama_alat ?? props.alat.nama }}
      </h3>

      <!-- Description (truncated) -->
      <p v-if="props.alat.deskripsi" class="text-gray-600 text-xs mt-1 line-clamp-2">
        {{ props.alat.deskripsi }}
      </p>

      <!-- Price -->
      <div class="mt-3 mb-3">
        <p class="text-sm text-gray-500">Harga per Hari</p>
        <p class="text-xl font-bold text-green-600">
          {{ formatCurrency(props.alat.harga_sewa_per_hari ?? props.alat.harga_sewa) }}
        </p>
      </div>

      <!-- Stock -->
      <div class="mb-4">
        <div class="flex justify-between items-center text-xs">
          <span class="text-gray-600">Stok Tersedia</span>
          <span :class="[
            'font-semibold',
            (props.alat.stok_tersedia ?? props.alat.stok) > 5 ? 'text-green-600' : 'text-orange-600'
          ]">
            {{ props.alat.stok_tersedia ?? props.alat.stok }} unit
          </span>
        </div>
        <!-- Stock Bar -->
        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
          <div
            :style="{
              width: `${Math.min(100, ((props.alat.stok_tersedia ?? props.alat.stok) / (props.alat.stok_total ?? 10)) * 100)}%`
            }"
            :class="[
              'h-1.5 rounded-full transition-all duration-300',
              (props.alat.stok_tersedia ?? props.alat.stok) > 5 ? 'bg-green-500' : 'bg-orange-500'
            ]"
          ></div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="grid grid-cols-2 gap-2 mt-auto px-4 pb-4 rounded-b-lg bg-white">
        <button
          @click="handleSewaClick"
          :disabled="isOutOfStock()"
          class="w-full bg-green-600 text-white text-sm font-semibold py-2.5 rounded-lg transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed hover:enabled:bg-green-700 active:enabled:scale-95"
        >
          🛒 Sewa
        </button>
        <Link
          :href="route('alat.show', props.alat.id)"
          class="w-full"
        >
          <button class="w-full border-2 border-gray-300 text-gray-700 text-sm font-semibold py-2.5 rounded-lg transition-all duration-200 hover:border-gray-400 hover:bg-gray-50 active:scale-95">
            📋 Detail
          </button>
        </Link>
      </div>
    </div>

    <!-- MODAL FORM -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 text-white p-6 flex justify-between items-center">
          <h2 class="text-xl font-bold">Pesan Penyewaan</h2>
          <button @click="closeModal" class="text-white hover:bg-white hover:bg-opacity-20 p-1 rounded">
            ✕
          </button>
        </div>

        <!-- Alat Info -->
        <div class="p-6 border-b">
          <h3 class="font-semibold text-gray-900">{{ props.alat.nama_alat }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ props.alat.deskripsi }}</p>
          <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
              <p class="text-xs text-gray-500">Harga/Hari</p>
              <p class="font-bold text-green-600">{{ formatCurrency(props.alat.harga_sewa_per_hari) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Stok</p>
              <p class="font-bold">{{ props.alat.stok_tersedia }} unit</p>
            </div>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input
              v-model="formData.tanggal_mulai"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
              :min="new Date().toISOString().split('T')[0]"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
            <input
              v-model="formData.tanggal_selesai"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
              :min="formData.tanggal_mulai || new Date().toISOString().split('T')[0]"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
            <input
              v-model.number="formData.jumlah"
              type="number"
              min="1"
              :max="props.alat.stok_tersedia"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
            <textarea
              v-model="formData.catatan"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
              rows="2"
            ></textarea>
          </div>

          <!-- Price Summary -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-700 mb-1">Estimasi Total:</p>
            <p class="text-2xl font-bold text-blue-900">{{ formatCurrency(totalPrice) }}</p>
            <p class="text-xs text-blue-600 mt-2">
              {{ formatCurrency(props.alat.harga_sewa_per_hari) }} × {{ rentalDays }} hari × {{ formData.jumlah }} unit
            </p>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3 pt-4">
            <button
              type="submit"
              :disabled="!canSubmit"
              class="flex-1 bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all duration-200"
            >
              <span v-if="isSubmitting" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
              </span>
              <span v-else>Pesan Sekarang</span>
            </button>
            <button
              type="button"
              @click="closeModal"
              class="flex-1 border-2 border-gray-300 text-gray-700 font-semibold py-2 rounded-lg hover:bg-gray-50 transition-all duration-200"
            >
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
