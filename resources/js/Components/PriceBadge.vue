<script setup>
import { formatRupiah } from '@/Utils/currency.js';
const props = defineProps({ price: { type: Number, required: true } });
</script>

<template>
  <div class="text-lg font-semibold text-gray-900">{{ formatRupiah(props.price) }} / hari</div>
</template>
