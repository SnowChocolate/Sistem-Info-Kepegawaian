<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Rekapitulasi Absensi</h1>
    <p class="text-sm text-gray-600">Riwayat log pencatatan absensi masuk dan keluar karyawan.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(!empty($rekap_absensi)): foreach($rekap_absensi as $a): ?>
                <tr class="text-sm text-gray-700">
                    <td class="px-6 py-4 font-medium"><?= $a['tanggal']; ?></td>
                    <td class="px-6 py-4"><?= $a['nama_pegawai']; ?></td>
                    <td class="px-6 py-4 text-green-600 font-semibold"><?= $a['jam_masuk'] ?? '--:--'; ?></td>
                    <td class="px-6 py-4 text-red-600 font-semibold"><?= $a['jam_pulang'] ?? '--:--'; ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-medium"><?= $a['status'] ?? 'Tepat Waktu'; ?></span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">Tidak ada log absensi bulan ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>