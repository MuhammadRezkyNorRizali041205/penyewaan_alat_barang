<script setup>
import ProductCard from '@/Components/ProductCard.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  alats: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const searchQuery = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.kategori_id || '');

function handleSearch() {
  router.visit('/alat', {
    method: 'get',
    data: {
      search: searchQuery.value || undefined,
      kategori_id: selectedCategory.value || undefined,
    },
  });
}

function handleFilterChange() {
  handleSearch();
}

function goToPage(url) {
  if (url) router.visit(url);
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 py-6">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Marketplace Penyewaan Alat</h1>
        <p class="text-gray-600">Temukan dan sewa alat berkualitas dengan harga terbaik</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Search & Filter Bar -->
      <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Search Input -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Alat</label>
            <div class="flex gap-2">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Ketik nama atau deskripsi alat..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                @keyup.enter="handleSearch"
              />
              <button
                @click="handleSearch"
                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium"
              >
                🔍 Cari
              </button>
            </div>
          </div>

          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select
              v-model="selectedCategory"
              @change="handleFilterChange"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <option value="">Semua Kategori</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.nama_kategori }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-if="!props.alats.data || props.alats.data.length === 0" class="flex flex-col items-center justify-center py-24 bg-white rounded-lg">
        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-gray-500 text-lg font-medium">Alat tidak ditemukan</p>
        <p class="text-gray-400 text-sm mt-1">Coba ubah pencarian atau filter Anda</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 auto-rows-fr">
        <ProductCard
          v-for="alat in props.alats.data"
          :key="alat.id"
          :alat="alat"
        />
      </div>

      <!-- Pagination -->
      <div v-if="props.alats.links && props.alats.links.length > 3" class="mt-12 flex justify-center">
        <nav class="inline-flex rounded-lg shadow-sm border border-gray-300 overflow-hidden">
          <template v-for="(link, i) in props.alats.links" :key="i">
            <button
              v-if="link.url"
              :disabled="!link.url"
              :class="[
                'px-4 py-2 text-sm font-medium transition',
                link.active
                  ? 'bg-green-600 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border-r border-gray-300 last:border-r-0',
              ]"
              @click="goToPage(link.url)"
              v-html="link.label"
            />
            <span
              v-else
              :class="[
                'px-4 py-2 text-sm font-medium bg-white text-gray-400 border-r border-gray-300 last:border-r-0',
              ]"
              v-html="link.label"
            />
          </template>
        </nav>
      </div>

      <!-- Back Button -->
      <div class="mt-12 flex justify-center">
        <a href="/" class="px-8 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
          ← Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Smooth transitions */
* {
  transition: all 0.2s ease;
}
</style>
