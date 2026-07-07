<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Form Pengajuan Cuti</h1>
    <p class="text-sm text-gray-600">Isi formulir pengajuan izin cuti kerja dengan benar.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form action="/cuti/proses_pengajuan" method="POST" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Alasan Keterangan Cuti</label>
            <textarea name="alasan" rows="3" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Cuti Urusan Pernikahan Keluarga"></textarea>
        </div>
        <div class="pt-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">Kirim Pengajuan</button>
        </div>
    </form>
</div>