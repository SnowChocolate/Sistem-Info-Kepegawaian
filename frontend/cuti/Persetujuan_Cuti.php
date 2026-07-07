<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Persetujuan Cuti Pegawai</h1>
    <p class="text-sm text-gray-600">Verifikasi berkas permintaan cuti dari staff internal.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Cuti</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alasan</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tindakan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            <?php if(!empty($data_approval)): foreach($data_approval as $a): ?>
            <tr>
                <td class="px-6 py-4 font-semibold text-gray-800"><?= $a['nama_pegawai']; ?></td>
                <td class="px-6 py-4 text-gray-600"><?= $a['tgl_mulai']; ?> s/d <?= $a['tgl_selesai']; ?></td>
                <td class="px-6 py-4 text-gray-500"><?= $a['alasan']; ?></td>
                <td class="px-6 py-4 space-x-2">
                    <a href="/cuti/approve?id=<?= $a['id']; ?>&status=setuju" class="px-3 py-1 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700">Setujui</a>
                    <a href="/cuti/approve?id=<?= $a['id']; ?>&status=tolak" class="px-3 py-1 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700">Tolak</a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada permohonan cuti masuk yang perlu diproses.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>