<?php
// =========================================================================
// 1. PINDAH KE ATAS: HANDLER AJAX RIWAYAT PEGAWAI (Mencegah Inception Effect)
// =========================================================================
if (isset($_GET['get_riwayat']) && isset($_GET['id']) && isset($koneksi)) {
    $id_url_ajax = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data pegawai internal untuk mendapatkan ID internal tabel pegawai
    $query_pegawai_internal = mysqli_query($koneksi, "SELECT id FROM pegawai WHERE id = '$id_url_ajax' LIMIT 1");
    $pegawai_internal = mysqli_fetch_assoc($query_pegawai_internal);
    
    if ($pegawai_internal) {
        $id_pegawai_db = $pegawai_internal['id'];
        $query_riwayat = mysqli_query($koneksi, "SELECT * FROM riwayat_pegawai WHERE id_pegawai = '$id_pegawai_db' ORDER BY id DESC");
        
        ?>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-gray-800">Log Riwayat / Mutasi Pegawai</h3>
        </div>
        <div class="overflow-x-auto border border-gray-100 rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php 
                    $no = 1;
                    if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0): 
                        while ($r = mysqli_fetch_assoc($query_riwayat)): 
                    ?>
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500"><?= $no++; ?></td>
                        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($r['tanggal'] ?? '-'); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($r['keterangan'] ?? '-'); ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada data riwayat kepegawaian.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<div class="text-center text-sm text-gray-400 py-8">Data pegawai tidak valid.</div>';
    }
    exit; // Berhenti di sini saat request AJAX aktif agar layout index.php tidak hancur
}

// 2. LOGIKA UPDATE DATABASE (Diproses saat tombol "Simpan Perubahan" diklik)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pegawai'])) {
    if (isset($koneksi)) {
        $id_pegawai = mysqli_real_escape_string($koneksi, $_POST['id_pegawai']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
        $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
        $gaji_pokok = mysqli_real_escape_string($koneksi, $_POST['gaji_pokok']);

        $query_update = "UPDATE pegawai SET nama='$nama', nip='$nip', jabatan='$jabatan', gaji_pokok='$gaji_pokok' WHERE id='$id_pegawai'";
        
        if (mysqli_query($koneksi, $query_update)) {
            echo "<script>alert('Data pegawai berhasil diperbarui!'); window.location.href='index.php?page=daftar_pegawai';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui data.');</script>";
        }
    } else {
        echo "<script>alert('Koneksi database tidak terdeteksi.');</script>";
    }
}

// 3. LOGIKA PENGAMBILAN DATA (Mengambil data berdasarkan ID dari URL)
$id_url = $_GET['id'] ?? null;
$pegawai = null;

if ($id_url && isset($koneksi)) {
    $query_ambil = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE id = '$id_url' LIMIT 1");
    $pegawai = mysqli_fetch_assoc($query_ambil);
}

if (!$pegawai) {
    echo "<script>alert('Data pegawai tidak ditemukan!'); window.location.href='index.php?page=daftar_pegawai';</script>";
    exit;
}
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detail & Kelola Profil Pegawai</h1>
    <a href="index.php?page=daftar_pegawai" class="text-sm text-blue-600 hover:underline"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="bg-slate-900 p-6 text-white flex items-center gap-4">
        <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center text-xl font-bold">
            <?= strtoupper(substr($pegawai['nama'] ?? 'U', 0, 1)); ?>
        </div>
        <div>
            <h2 class="text-xl font-bold"><?= htmlspecialchars($pegawai['nama'] ?? '-'); ?></h2>
            <p class="text-xs text-slate-400">NIP: <?= !empty($pegawai['nip']) ? htmlspecialchars($pegawai['nip']) : 'Belum Diatur'; ?></p>
        </div>
    </div>
    
    <div class="flex border-b border-gray-100 bg-gray-50 px-4">
        <button id="btn-tab-profil" class="px-4 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 focus:outline-none transition-colors">
            Detail Profil
        </button>
        <button id="btn-tab-riwayat" class="px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
            Riwayat Pegawai
        </button>
    </div>

    <div id="wadah-konten-detail">
        <form action="" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_pegawai" value="<?= $pegawai['id']; ?>">

            <div class="grid grid-cols-3 items-center text-sm gap-4">
                <label class="text-gray-500 font-medium">Nama Lengkap</label>
                <div class="col-span-2">
                    <input type="text" name="nama" required value="<?= htmlspecialchars($pegawai['nama'] ?? ''); ?>" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 font-medium">
                </div>
            </div>

            <div class="grid grid-cols-3 items-center text-sm gap-4">
                <label class="text-gray-500 font-medium">NIP Pegawai</label>
                <div class="col-span-2">
                    <input type="text" name="nip" placeholder="Masukkan NIP baru" value="<?= htmlspecialchars($pegawai['nip'] ?? ''); ?>" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-3 items-center text-sm gap-4">
                <label class="text-gray-500 font-medium">Jabatan</label>
                <div class="col-span-2">
                    <input type="text" name="jabatan" placeholder="Contoh: Staff IT, HRD" value="<?= htmlspecialchars($pegawai['jabatan'] ?? ''); ?>" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-3 items-center text-sm gap-4">
                <label class="text-gray-500 font-medium">Gaji Pokok (Rp)</label>
                <div class="col-span-2">
                    <input type="number" name="gaji_pokok" placeholder="Contoh: 4500000" value="<?= htmlspecialchars($pegawai['gaji_pokok'] ?? 0); ?>" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800">
                </div>
            </div>

            <div class="grid grid-cols-3 items-center text-sm gap-4 pt-2">
                <span class="text-gray-500 font-medium">Status Akun</span>
                <div class="col-span-2">
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" name="update_pegawai" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabProfil = document.getElementById("btn-tab-profil");
    const tabRiwayat = document.getElementById("btn-tab-riwayat");
    const wadahKonten = document.getElementById("wadah-konten-detail");

    const htmlFormProfilBawaan = wadahKonten.innerHTML;

    tabRiwayat.addEventListener("click", function () {
        tabProfil.className = "px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition-colors";
        tabRiwayat.className = "px-4 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 focus:outline-none transition-colors";
        
        wadahKonten.innerHTML = `<div class="p-6 text-center text-sm text-gray-500">Memuat riwayat...</div>`;

        // AMBIL ID PEGAWAI DARI INPUT HIDDEN FORM
        const idPegawai = document.querySelector('input[name="id_pegawai"]').value;

        // DIUBAH: Menembak rute API riwayat bersih
        fetch(`index.php?page=api_riwayat_pegawai&id=${idPegawai}`)
            .then(response => response.text())
            .then(html => {
                wadahKonten.innerHTML = `<div class="p-6">${html}</div>`;
            });
    });

    tabProfil.addEventListener("click", function () {
        tabRiwayat.className = "px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition-colors";
        tabProfil.className = "px-4 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 focus:outline-none transition-colors";
        
        wadahKonten.innerHTML = htmlFormProfilBawaan;
    });
});
</script>