<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detail Profil Pegawai</h1>
    <a href="/pegawai" class="text-sm text-blue-600 hover:underline"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="bg-slate-900 p-6 text-white flex items-center gap-4">
        <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center text-xl font-bold">
            <?= strtoupper(substr($pegawai['nama'] ?? 'U', 0, 1)); ?>
        </div>
        <div>
            <h2 class="text-xl font-bold"><?= $pegawai['nama'] ?? '-'; ?></h2>
            <p class="text-xs text-slate-400">NIP: <?= $pegawai['nip'] ?? '-'; ?></p>
        </div>
    </div>
    <div class="p-6 divide-y divide-gray-100">
        <div class="py-3 grid grid-cols-3 text-sm">
            <span class="text-gray-500 font-medium">Jabatan</span>
            <span class="col-span-2 text-gray-800 font-semibold"><?= $pegawai['jabatan'] ?? '-'; ?></span>
        </div>
        <div class="py-3 grid grid-cols-3 text-sm">
            <span class="text-gray-500 font-medium">Gaji Pokok</span>
            <span class="col-span-2 text-gray-800">Rp <?= number_format($pegawai['gaji_pokok'] ?? 0, 0, ',', '.'); ?></span>
        </div>
        <div class="py-3 grid grid-cols-3 text-sm">
            <span class="text-gray-500 font-medium">Status Akun</span>
            <span class="col-span-2"><span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span></span>
        </div>
    </div>
</div>