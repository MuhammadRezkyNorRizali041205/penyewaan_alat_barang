<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Daftar Penyewaan
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="mb-6">
              <Link
                href="/dashboard"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
              >
                ← Kembali
              </Link>
            </div>

            <div v-if="penyewaans.data.length === 0" class="text-center py-12">
              <p class="text-gray-500">Tidak ada data penyewaan</p>
            </div>

            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Penyewa
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="penyewaan in penyewaans.data" :key="penyewaan.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      #{{ penyewaan.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ penyewaan.penyewa.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(penyewaan.tanggal_mulai) }} - {{ formatDate(penyewaan.tanggal_selesai) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <span :class="statusBadgeClass(penyewaan.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ penyewaan.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                      <Link
                        :href="route('penyewaan.show', penyewaan.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Lihat
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="penyewaans.links" class="mt-6 flex justify-center">
              <div class="flex space-x-1">
                <Link
                  v-for="link in penyewaans.links"
                  :key="link.label"
                  :href="link.url"
                  :class="link.active ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'border-gray-300 text-gray-500 hover:bg-gray-50'"
                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                  v-html="link.label"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  penyewaans: {
    type: Object,
    required: true,
  },
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

const statusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-50 text-yellow-800',
    approved: 'bg-green-50 text-green-800',
    rejected: 'bg-red-50 text-red-800',
    returned: 'bg-blue-50 text-blue-800',
  };
  return classes[status] || 'bg-gray-50 text-gray-800';
};
</script>
