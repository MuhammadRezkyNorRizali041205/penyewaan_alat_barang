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
  <div class="bg-white rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1">
    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center overflow-hidden">
      <img v-if="props.alat.gambar_url" :src="props.alat.gambar_url" :alt="props.alat.nama_alat" class="w-full h-full object-cover" loading="lazy" />
      <span v-else class="text-gray-400">No Image</span>
    </div>
    <div class="p-4">
      <h3 class="font-semibold text-sm truncate">{{ props.alat.nama_alat ?? props.alat.nama }}</h3>
      <p class="text-lg font-bold text-green-600 mt-2">Rp {{ formatHarga(props.alat.harga_sewa_per_hari ?? props.alat.harga_sewa) }} / hari</p>
      <p class="text-xs text-gray-500 mt-1">Stok: {{ props.alat.stok_tersedia ?? props.alat.stok }}</p>
      <div class="mt-3 flex gap-2">
        <Link :href="route('penyewaan.create', { alat_id: props.alat.id })" class="flex-1">
          <button :disabled="(props.alat.stok_tersedia ?? props.alat.stok) <= 0" class="w-full bg-green-600 disabled:opacity-50 text-white text-sm rounded py-2">Sewa</button>
        </Link>
        <Link :href="route('alat.show', props.alat.id)" class="flex-1">
          <button class="w-full border rounded py-2 text-sm">Detail</button>
        </Link>
      </div>
    </div>
  </div>
</template>
