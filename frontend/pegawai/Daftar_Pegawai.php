<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pegawai</h1>
        <p class="text-sm text-gray-600">Kelola informasi profile, posisi, dan status kepegawaian.</p>
    </div>
    <a href="/pegawai/tambah" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
        <i class="fa-solid fa-plus text-xs"></i> Tambah Pegawai
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(!empty($data_pegawai)): $no=1; foreach($data_pegawai as $p): ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-600"><?= $no++; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700"><?= $p['nip']; ?></td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= $p['nama']; ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= $p['jabatan']; ?></td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="/pegawai/detail?id=<?= $p['id']; ?>" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></a>
                        <a href="/pegawai/hapus?id=<?= $p['id']; ?>" onclick="return confirm('Hapus data pegawai ini?')" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada data pegawai.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>