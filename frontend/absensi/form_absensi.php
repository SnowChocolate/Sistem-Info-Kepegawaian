<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Presensi Kehadiran</h1>
    <p class="text-sm text-gray-600">Catat jam masuk dan jam pulang kerja Anda di sini.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-md text-center">
    <div class="mb-4">
        <p class="text-sm text-gray-400 font-medium uppercase tracking-widest">Waktu Sekarang</p>
        <h2 class="text-3xl font-extrabold text-gray-800 mt-1"><?= date('H:i'); ?> WIB</h2>
        <p class="text-xs text-gray-500 mt-1"><?= date('d F Y'); ?></p>
    </div>

    <form action="/absensi/proses_absen" method="POST" class="space-y-4 mt-6">
        <div class="grid grid-cols-2 gap-4">
            <button type="submit" name="jenis" value="masuk" class="py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-right-to-bracket mb-1 block text-lg"></i> Absen Masuk
            </button>
            <button type="submit" name="jenis" value="pulang" class="py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-right-from-bracket mb-1 block text-lg"></i> Absen Pulang
            </button>
        </div>
    </form>
</div>