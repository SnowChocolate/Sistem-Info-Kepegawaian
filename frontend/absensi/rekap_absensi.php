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
                <?php
// Di dalam file frontend/absensi/rekap_absensi.php

/** @var mysqli $conn */
/** @var mysqli $koneksi */
$koneksi_db = $conn ?? $koneksi;

$id_user = $_SESSION['id_user'] ?? 0;
$role = $_SESSION['role'] ?? 'pegawai'; // Pastikan session role Anda bernama 'admin' / 'pegawai'

// KONDISI TAMPILAN DATA
if ($role === 'admin') {
    // Jika Admin, ambil data absensi SELURUH pegawai
    $query = "SELECT absensi.*, pegawai.nama, pegawai.jabatan 
              FROM absensi 
              JOIN pegawai ON absensi.id_user = pegawai.id_user 
              ORDER BY absensi.tanggal DESC, absensi.jam_masuk DESC";
} else {
    // Jika Pegawai biasa, HANYA ambil data milik dia sendiri
    $query = "SELECT absensi.*, pegawai.nama, pegawai.jabatan 
              FROM absensi 
              JOIN pegawai ON absensi.id_user = pegawai.id_user 
              WHERE absensi.id_user = '$id_user' 
              ORDER BY absensi.tanggal DESC";
}

$result = mysqli_query($koneksi_db, $query);
?>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>
<tr class="border-b border-gray-100 hover:bg-gray-50 text-gray-700">
    <td class="px-6 py-4"><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
    <td class="px-6 py-4 font-semibold"><?= $row['nama']; ?> (<?= $row['jabatan']; ?>)</td>
    <td class="px-6 py-4 text-emerald-600 font-medium"><?= $row['jam_masuk']; ?></td>
    <td class="px-6 py-4 text-orange-600 font-medium"><?= $row['jam_pulang'] ?? '-- : --'; ?></td>
    <td class="px-6 py-4">
        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
            <?= $row['status']; ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>