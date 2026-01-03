<div class="p-6 bg-gradient-to-br from-slate-800 via-slate-900 to-black rounded-lg shadow-lg border border-slate-700">
    <h2 class="text-xl font-bold mb-4 text-white">📊 Export Data Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Export Penyewaan -->
        <button wire:click="exportPenyewaan"
                class="inline-flex flex-col items-center justify-center px-6 py-4 bg-gradient-to-br from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white rounded-lg font-medium transition transform hover:scale-105 shadow-md">
            <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export Penyewaan</span>
        </button>

        <!-- Export Pengembalian -->
        <button wire:click="exportPengembalian"
                class="inline-flex flex-col items-center justify-center px-6 py-4 bg-gradient-to-br from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white rounded-lg font-medium transition transform hover:scale-105 shadow-md">
            <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export Pengembalian</span>
        </button>

        <!-- Export Denda -->
        <button wire:click="exportDenda"
                class="inline-flex flex-col items-center justify-center px-6 py-4 bg-gradient-to-br from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white rounded-lg font-medium transition transform hover:scale-105 shadow-md">
            <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export Denda</span>
        </button>
    </div>
</div>
