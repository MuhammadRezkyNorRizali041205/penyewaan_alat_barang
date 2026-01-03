<script setup>
import ProductCard from '@/Components/ProductCard.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  alats: {
    type: Object,
    required: true,
  },
});

function goToPage(url) {
  if (url) router.visit(url);
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Marketplace Penyewaan Alat</h1>
    <div v-if="!props.alats.data || props.alats.data.length === 0" class="flex flex-col items-center justify-center py-24">
      <p class="text-gray-500 text-lg">Belum ada alat tersedia.</p>
    </div>
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <ProductCard
        v-for="alat in props.alats.data"
        :key="alat.id"
        :alat="alat"
      />
    </div>
    <div v-if="props.alats.links && props.alats.links.length > 3" class="mt-8 flex justify-center">
      <nav class="inline-flex rounded-md shadow-sm">
        <template v-for="(link, i) in props.alats.links" :key="i">
          <button
            v-if="link.url"
            :class="[
              'px-3 py-1 border text-sm',
              link.active ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
              i === 0 ? 'rounded-l-md' : '',
              i === props.alats.links.length - 1 ? 'rounded-r-md' : '',
            ]"
            @click="goToPage(link.url)"
            v-html="link.label"
          />
          <span
            v-else
            class="px-3 py-1 border text-sm bg-white text-gray-400"
            v-html="link.label"
          />
        </template>
      </nav>
    </div>
  </div>
</template>
