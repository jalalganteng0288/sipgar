<x-app-layout>
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 mb-8 overflow-hidden relative">
        <div class="relative z-10">
            <h3 class="text-3xl font-extrabold text-slate-900 leading-tight italic">
                Selamat Datang Kembali, {{ Auth::user()->name }}! 👋
            </h3>
            <p class="mt-2 text-slate-500 font-medium">Berikut adalah ringkasan data terbaru dari Sistem Informasi Perumahan Garut.</p>
        </div>
        <div class="absolute right-0 top-0 opacity-5 -mr-12 -mt-12 pointer-events-none">
            <svg class="w-64 h-64 text-indigo-900" fill="currentColor" viewBox="0 0 200 200"><path d="M43.3,-62.4C55.9,-54.6,65.9,-41.8,71.7,-27.4C77.4,-13,78.9,3.1,74.7,18.1C70.4,33.1,60.4,47.1,47.3,56.8C34.2,66.5,18.1,71.9,1.7,69.5C-14.7,67.2,-29.4,57.1,-41.5,46.8C-53.5,36.5,-62.9,26,-67.2,13.5C-71.5,1.1,-70.8,-13.2,-65.1,-25.9C-59.4,-38.6,-48.8,-49.6,-36.8,-57.8C-24.8,-66,-12.4,-71.4,1.8,-74.1C16.1,-76.8,30.7,-70.2,43.3,-62.4Z" transform="translate(100 100)" /></svg>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-500 rounded-2xl p-6 text-white shadow-lg transition-transform hover:scale-105">
            <svg class="h-8 w-8 opacity-70 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6m-6 4.5h6"/></svg>
            <h4 class="text-3xl font-bold italic">{{ number_format($totalProjects) }}</h4>
            <p class="text-xs font-semibold uppercase opacity-80 tracking-widest mt-1">Total Proyek</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-teal-500 rounded-2xl p-6 text-white shadow-lg transition-transform hover:scale-105">
            <svg class="h-8 w-8 opacity-70 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.284.126"/></svg>
            <h4 class="text-3xl font-bold italic">{{ number_format($totalDevelopers) }}</h4>
            <p class="text-xs font-semibold uppercase opacity-80 tracking-widest mt-1">Total Developer</p>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg transition-transform hover:scale-105">
            <svg class="h-8 w-8 opacity-70 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-3 1.091m0 0l-3-1.091m0 0l-1.5-.545M12 12l-3 1.091" /></svg>
            <h4 class="text-3xl font-bold italic">{{ number_format($totalUnits) }}</h4>
            <p class="text-xs font-semibold uppercase opacity-80 tracking-widest mt-1">Total Unit</p>
        </div>

        <div class="bg-gradient-to-br from-rose-600 to-pink-500 rounded-2xl p-6 text-white shadow-lg transition-transform hover:scale-105">
            <svg class="h-8 w-8 opacity-70 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2.25 21h19.5m-18-18h16.5M5.25 6h13.5m-13.5 4.5h13.5m-13.5 4.5h13.5M5.25 21V3" /></svg>
            <h4 class="text-3xl font-bold italic">{{ number_format($totalUnitsSold) }}</h4>
            <p class="text-xs font-semibold uppercase opacity-80 tracking-widest mt-1">Unit Terjual</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 uppercase tracking-tighter">
            <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
            Aksi Cepat
        </h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.projects.create') }}"
               class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Proyek Baru
            </a>
        </div>
    </div>
</x-app-layout>