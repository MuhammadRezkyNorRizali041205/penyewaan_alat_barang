<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const isSubmitting = ref(false);
const showSuccess = ref(false);

const props = defineProps({
  selectedAlat: {
    type: Object,
    default: null,
  },
});

const form = ref({
  tanggal_mulai: '',
  tanggal_selesai: '',
  jumlah: 1,
  catatan: '',
});

const isAuthenticated = computed(() => {
  return page.props.auth && page.props.auth.user;
});

const canSubmit = computed(() => {
  return (
    isAuthenticated.value &&
    form.value.tanggal_mulai &&
    form.value.tanggal_selesai &&
    form.value.jumlah > 0 &&
    props.selectedAlat &&
    form.value.jumlah <= props.selectedAlat.stok_tersedia &&
    !isSubmitting.value
  );
});

const submit = () => {
  if (!isAuthenticated.value) {
    window.location.href = '/login?redirect=/penyewaan/create?alat_id=' + props.selectedAlat.id;
    return;
  }

  if (!props.selectedAlat) {
    alert('Silakan pilih alat terlebih dahulu');
    return;
  }

  isSubmitting.value = true;

  router.post(
    '/penyewaan',
    {
      alat_id: props.selectedAlat.id,
      tanggal_mulai: form.value.tanggal_mulai,
      tanggal_selesai: form.value.tanggal_selesai,
      jumlah: parseInt(form.value.jumlah),
      catatan: form.value.catatan || null,
    },
    {
      onSuccess: (response) => {
        showSuccess.value = true;
        // Get the penyewaan ID from the response
        const penyewaanId = response.props.penyewaan?.id;

        // Redirect after showing success animation
        setTimeout(() => {
          router.visit(`/penyewaan/${penyewaanId}`, { method: 'get' });
        }, 1500);
      },
      onError: (errors) => {
        isSubmitting.value = false;
        console.error('Validation errors:', errors);
        alert('Gagal mengajukan penyewaan: ' + Object.values(errors).join(', '));
      },
      onFinish: () => {
        isSubmitting.value = false;
      },
    }
  );
};
</script>

<template>
  <div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Success Animation -->
    <transition name="fade">
      <div v-if="showSuccess" class="fixed inset-0 flex items-center justify-center pointer-events-none z-50">
        <div class="animate-bounce bg-green-500 rounded-full p-8 mb-4">
          <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
      </div>
    </transition>

    <h1 class="text-2xl font-bold mb-6">Ajukan Penyewaan</h1>

    <div v-if="props.selectedAlat" class="bg-white rounded-lg p-6 mb-6">
      <h2 class="text-lg font-semibold mb-2">{{ props.selectedAlat.nama_alat }}</h2>
      <p class="text-gray-600 mb-4">{{ props.selectedAlat.deskripsi }}</p>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <span class="text-sm text-gray-500">Harga per Hari</span>
          <p class="font-bold text-green-600">Rp {{ Number(props.selectedAlat.harga_sewa_per_hari).toLocaleString('id-ID') }}</p>
        </div>
        <div>
          <span class="text-sm text-gray-500">Stok Tersedia</span>
          <p class="font-bold">{{ props.selectedAlat.stok_tersedia }} unit</p>
        </div>
      </div>
    </div>

    <form @submit.prevent="submit" class="bg-white rounded-lg p-6 space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
        <input v-model="form.tanggal_mulai" type="date" class="w-full border rounded-lg p-2" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
        <input v-model="form.tanggal_selesai" type="date" class="w-full border rounded-lg p-2" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
        <input v-model="form.jumlah" type="number" min="1" class="w-full border rounded-lg p-2" :max="props.selectedAlat?.stok_tersedia || 1" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
        <textarea v-model="form.catatan" class="w-full border rounded-lg p-2" rows="3"></textarea>
      </div>

      <div class="flex gap-4 pt-4">
        <button
          type="submit"
          :disabled="!canSubmit"
          class="flex-1 bg-green-600 text-white rounded-lg py-2 font-medium hover:bg-green-700 disabled:bg-gray-400 transition-all duration-200"
          :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }"
        >
          <span v-if="isSubmitting" class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sedang Diproses...
          </span>
          <span v-else>{{ isAuthenticated ? 'Ajukan' : 'Login untuk Mengajukan' }}</span>
        </button>
        <Link href="/alat" class="flex-1">
          <button type="button" class="w-full border rounded-lg py-2 font-medium hover:bg-gray-100">
            Kembali
          </button>
        </Link>
      </div>
    </form>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@keyframes bounce {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-1rem);
  }
}

.animate-bounce {
  animation: bounce 1s infinite;
}
</style>
