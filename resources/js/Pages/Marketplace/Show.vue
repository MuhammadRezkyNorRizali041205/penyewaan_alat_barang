<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  alat: {
    type: Object,
    required: true,
  },
});

function formatHarga(value) {
  if (!value) return '0';
  return Number(value).toLocaleString('id-ID');
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg p-4">
          <div class="h-96 bg-gray-100 flex items-center justify-center overflow-hidden rounded">
            <img v-if="props.alat.gambar_url" :src="props.alat.gambar_url" :alt="props.alat.nama_alat" class="w-full h-full object-cover" loading="lazy" />
            <span v-else class="text-gray-400">No Image</span>
          </div>
        </div>
        <div class="mt-6 bg-white rounded-lg p-4">
          <h2 class="text-xl font-semibold">Deskripsi</h2>
          <p class="mt-2 text-gray-700">{{ props.alat.deskripsi || 'Tidak ada deskripsi' }}</p>
        </div>
      </div>
      <aside class="space-y-4">
        <div class="bg-white rounded-lg p-4">
          <h3 class="text-lg font-semibold">{{ props.alat.nama_alat ?? props.alat.nama }}</h3>
          <div class="text-sm text-gray-500 mt-2">Harga</div>
          <div class="text-2xl font-bold text-green-600">Rp {{ formatHarga(props.alat.harga_sewa_per_hari ?? props.alat.harga_sewa) }} / hari</div>
          <div class="mt-4">
            <div class="text-sm text-gray-500">Stok Tersedia</div>
            <div class="font-medium">{{ props.alat.stok_tersedia ?? props.alat.stok }} unit</div>
          </div>
          <div class="mt-6">
            <Link :href="route('penyewaan.create', { alat_id: props.alat.id })">
              <button :disabled="(props.alat.stok_tersedia ?? props.alat.stok) <= 0" class="w-full bg-green-600 disabled:opacity-50 text-white rounded py-2 font-medium">Ajukan Penyewaan</button>
            </Link>
          </div>
        </div>
        <div class="bg-white rounded-lg p-4">
          <div class="text-sm text-gray-500">Kategori</div>
          <div class="font-medium">{{ props.alat.kategori?.nama_kategori ?? props.alat.kategori?.nama ?? 'Umum' }}</div>
        </div>
      </aside>
    </div>
  </div>
</template>
