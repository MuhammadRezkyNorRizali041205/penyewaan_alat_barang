<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  alat: {
    type: Object,
    required: true,
  },
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
</script>

<template>
  <div :class="[
    'group relative bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300',
    'hover:shadow-xl hover:scale-105',
    isOutOfStock() ? 'opacity-60' : ''
  ]">
    <!-- Out of Stock Badge -->
    <div v-if="isOutOfStock()" class="absolute top-2 right-2 z-10 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
      Habis
    </div>

    <!-- Image Container -->
    <div class="relative w-full h-52 bg-gradient-to-br from-gray-200 to-gray-100 overflow-hidden">
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
      <div class="grid grid-cols-2 gap-2 mt-auto">
        <Link
          :href="route('penyewaan.create', { alat_id: props.alat.id })"
          class="w-full"
        >
          <button
            :disabled="isOutOfStock()"
            class="w-full bg-green-600 text-white text-sm font-semibold py-2.5 rounded-lg transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed hover:enabled:bg-green-700 active:enabled:scale-95"
          >
            🛒 Sewa
          </button>
        </Link>
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
