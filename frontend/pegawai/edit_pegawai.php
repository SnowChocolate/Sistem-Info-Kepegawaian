<?php
// 1. Hubungkan ke database
require_once __DIR__ . '/../../backend/config/koneksi.php';

// 2. Ambil ID pegawai dari URL
$id_pegawai = $_GET['id'] ?? '';

if (empty($id_pegawai)) {
    echo "<div class='bg-red-50 text-red-600 p-4 rounded-lg font-medium shadow-sm m-6'>⚠️ Error: ID Pegawai tidak ditemukan atau kosong.</div>";
    exit;
}

// 3. Tarik data pegawai lama berdasarkan ID untuk dimasukkan ke dalam form
$database = new Database();
$db = $database->conn;

// Sesuaikan nama tabel 'pegawai' dan kolom 'id_pegawai' dengan database Anda
$query = "SELECT * FROM pegawai WHERE id_user = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $id_pegawai);
$stmt->execute();
$result = $stmt->get_result();
$pegawai = $result->fetch_assoc();

// Jika data tidak ditemukan di database
if (!$pegawai) {
    echo "<div class='bg-amber-50 text-amber-600 p-4 rounded-lg font-medium shadow-sm m-6'>⚠️ Data pegawai dengan ID tersebut tidak ditemukan di database.</div>";
    exit;
}
?>

<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mx-auto mt-4">
    <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-slate-800 rounded-xl text-amber-400 shadow-inner">
                <i class="fa-solid fa-user-pen text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold tracking-wide">Edit Informasi Pegawai</h2>
                <p class="text-xs text-slate-400 mt-0.5">Perbarui berkas data profil dan status operasional kerja pegawai.</p>
            </div>
        </div>
        <a href="index.php?page=daftar_pegawai" class="text-xs px-3.5 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-gray-300 font-medium transition-all flex items-center shadow-sm">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
        </a>
    </div>

    <form action="backend/proses/proses_edit_pegawai.php" method="POST" class="p-6 space-y-6">
        
        <input type="hidden" name="id_pegawai" value="<?= htmlspecialchars($pegawai['id_pegawai'] ?? ''); ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <i class="fa-solid fa-id-card text-sm"></i>
                    </span>
                    <input type="text" name="nip" value="<?= htmlspecialchars($pegawai['nip'] ?? ''); ?>" 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap Pegawai</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" name="nama" value="<?= htmlspecialchars($pegawai['nama'] ?? ''); ?>" 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium" required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan / Posisi Kerja</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <i class="fa-solid fa-briefcase text-sm"></i>
                    </span>
                    <input type="text" name="jabatan" value="<?= htmlspecialchars($pegawai['jabatan'] ?? ''); ?>" 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Kepegawaian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-circle-info text-sm"></i>
                    </span>
                    <select name="status" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium appearance-none cursor-pointer" required>
                        <option value="Tetap" <?= ($pegawai['status'] ?? '') == 'Tetap' ? 'selected' : ''; ?>>Pegawai Tetap</option>
                        <option value="Kontrak" <?= ($pegawai['status'] ?? '') == 'Kontrak' ? 'selected' : ''; ?>>Pegawai Kontrak</option>
                        <option value="Magang" <?= ($pegawai['status'] ?? '') == 'Magang' ? 'selected' : ''; ?>>Magang (Internship)</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="pt-5 border-t border-gray-100 flex justify-end space-x-3">
            <a href="index.php?page=daftar_pegawai" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl text-sm hover:bg-gray-100 transition-all shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl text-sm hover:bg-indigo-700 shadow-md hover:shadow-indigo-200 transition-all flex items-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>