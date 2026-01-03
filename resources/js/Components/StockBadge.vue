<script setup>
const props = defineProps({ stok: { type: Number, required: true } });

const status = computed(() => {
  if (props.stok <= 0) return { label: 'Habis', color: 'bg-red-100 text-red-800' };
  return { label: 'Tersedia', color: 'bg-green-100 text-green-800' };
});
</script>

<template>
  <span :class="`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${status.color}`">
    {{ status.label }} • Stok: {{ props.stok }}
  </span>
</template>
