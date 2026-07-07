<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Cuti Saya</h1>
        <p class="text-sm text-gray-600">Status pelacakan lembar persetujuan izin cuti.</p>
    </div>
    <a href="/cuti/tambah" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">Ajukan Cuti</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi Cuti</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            <?php if(!empty($riwayat_cuti)): foreach($riwayat_cuti as $c): ?>
            <tr>
                <td class="px-6 py-4 text-gray-700 font-medium"><?= $c['tgl_mulai']; ?> s/d <?= $c['tgl_selesai']; ?></td>
                <td class="px-6 py-4 text-gray-600"><?= $c['alasan']; ?></td>
                <td class="px-6 py-4">
                    <?php if($c['status'] == 'Disetujui'): ?>
                        <span class="px-2.5 py-0.5 bg-green-50 text-green-700 rounded-full font-semibold text-xs">Disetujui</span>
                    <?php elseif($c['status'] == 'Ditolak'): ?>
                        <span class="px-2.5 py-0.5 bg-red-50 text-red-700 rounded-full font-semibold text-xs">Ditolak</span>
                    <?php else: ?>
                        <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full font-semibold text-xs">Pending</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="3" class="px-6 py-10 text-center text-gray-400">Belum pernah mengajukan cuti.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>