<?php
$nama_db = "sistem_info_kepegawaian"; 
$koneksi_direct = mysqli_connect("localhost", "root", "", $nama_db); 

if (!$koneksi_direct) {
    die("<div class='bg-red-100 p-4 rounded text-red-700 font-bold'>Gagal koneksi! " . mysqli_connect_error() . "</div>");
}

// ==========================================
// PERBAIKAN: DISINKRONKAN DENGAN JAVASCRIPT FETCH
// ==========================================
if (isset($_GET['action_ajax']) && $_GET['action_ajax'] === 'cari_pegawai') {
    $keyword = mysqli_real_escape_string($koneksi_direct, trim($_GET['keyword'] ?? ''));
    $where_ajax = "";
    if (!empty($keyword)) {
        $where_ajax = " AND (u.username LIKE '%$keyword%' OR p.nama LIKE '%$keyword%' OR p.jabatan LIKE '%$keyword%')";
    }
    
    $query_ajax = mysqli_query($koneksi_direct, "
        SELECT u.id AS id_user, u.username, p.id AS id_pegawai, p.nama, p.jabatan, p.sisa_cuti 
        FROM users u 
        LEFT JOIN pegawai p ON u.id = p.id_user 
        WHERE u.role = 'pegawai' $where_ajax
        ORDER BY u.id DESC
    ");
    
    $no = 1;
    if (mysqli_num_rows($query_ajax) > 0) {
        while ($p = mysqli_fetch_assoc($query_ajax)) {
            ?>
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-600"><?= $no++; ?></td>
                <td class="px-6 py-4 text-sm font-semibold text-gray-700"><?= htmlspecialchars($p['username']); ?></td>
                <td class="px-6 py-4 text-sm text-gray-800"><?= (!empty($p['nama'])) ? htmlspecialchars($p['nama']) : htmlspecialchars($p['username']); ?></td>
                <td class="px-6 py-4 text-sm text-gray-600"><?= (!empty($p['jabatan'])) ? htmlspecialchars($p['jabatan']) : 'Staff'; ?></td>
                <td class="px-6 py-4 text-sm text-gray-600"><?= !empty($p['sisa_cuti']) ? htmlspecialchars($p['sisa_cuti']) : '12'; ?> Hari</td>
                <td class="px-6 py-4 text-sm space-x-3">
                    <a href="index.php?page=edit_pegawai&id=<?= $p['id_user']; ?>" class="text-amber-500 hover:text-amber-700 font-medium inline-flex items-center gap-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <a href="index.php?page=daftar_pegawai&aksi=hapus&id=<?= $p['id_user']; ?>" onclick="return confirm('Hapus data pegawai ini beserta akunnya?')" class="text-red-600 hover:text-red-800 font-medium inline-flex items-center gap-1">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">Data pegawai tidak ditemukan.</td></tr>';
    }
    exit; // Berhenti di sini agar layout utama tidak ikut hancur
}

// Fitur Pencarian Bawaan untuk Load Pertama Kali (POST)
$keyword = "";
$where_clause = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi_direct, trim($_POST['keyword']));
    if (!empty($keyword)) {
        $where_clause = " AND (u.username LIKE '%$keyword%' OR p.nama LIKE '%$keyword%' OR p.jabatan LIKE '%$keyword%')";
    }
}

$data_pegawai = [];
$query = mysqli_query($koneksi_direct, "
    SELECT u.id AS id_user, u.username, p.id AS id_pegawai, p.nama, p.jabatan, p.sisa_cuti 
    FROM users u 
    LEFT JOIN pegawai p ON u.id = p.id_user 
    WHERE u.role = 'pegawai' $where_clause
    ORDER BY u.id DESC
");

if (!$query) {
    die("<div class='bg-red-100 p-4 rounded text-red-700 font-bold'>Query ERROR: " . mysqli_error($koneksi_direct) . "</div>");
}

while ($row = mysqli_fetch_assoc($query)) {
    $data_pegawai[] = $row;
}
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pegawai</h1>
        <p class="text-sm text-gray-600">Kelola informasi profil, posisi, dan status kepegawaian.</p>
    </div>
    
    <form id="form-pencarian" action="" method="POST" class="flex items-center gap-2 w-full sm:w-auto" onsubmit="return false;">
        <input type="text" id="input-keyword" name="keyword" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari nama / jabatan..." 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800 w-full sm:w-64">
        <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center gap-1">
            <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari
        </button>
        <?php if(!empty($keyword)): ?>
            <a href="index.php?page=daftar_pegawai" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition-colors">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa Cuti</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="wadah-data-pegawai" class="divide-y divide-gray-100">
                <?php if(!empty($data_pegawai)): $no=1; foreach($data_pegawai as $p): ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-600"><?= $no++; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700"><?= htmlspecialchars($p['username']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= (!empty($p['nama'])) ? htmlspecialchars($p['nama']) : htmlspecialchars($p['username']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= (!empty($p['jabatan'])) ? htmlspecialchars($p['jabatan']) : 'Staff'; ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= !empty($p['sisa_cuti']) ? htmlspecialchars($p['sisa_cuti']) : '12'; ?> Hari</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <a href="index.php?page=edit_pegawai&id=<?= $p['id_user']; ?>" class="text-amber-500 hover:text-amber-700 font-medium inline-flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="index.php?page=daftar_pegawai&aksi=hapus&id=<?= $p['id_user']; ?>" onclick="return confirm('Hapus data pegawai ini beserta akunnya?')" class="text-red-600 hover:text-red-800 font-medium inline-flex items-center gap-1">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">Data pegawai tidak ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputKeyword = document.getElementById("input-keyword");
    const wadahData = document.getElementById("wadah-data-pegawai");

    function jalankanAjax(keyword) {
        // PERBAIKAN: Parameter disamakan dengan validasi PHP di atas 
        // Menggunakan URL dinamis agar tetap sinkron dengan halaman tempat ia berada
        fetch(`index.php?action_ajax=cari_pegawai&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.text())
            .then(html => {
                wadahData.innerHTML = html;
            })
            .catch(err => console.error("Gagal memuat data AJAX:", err));
    }

    inputKeyword.addEventListener("keyup", function () {
        jalankanAjax(this.value);
    });
});

function hapusPegawai(idPegawai) {
    // Tampilkan konfirmasi pop-up bawaan browser terlebih dahulu
    if (confirm("Apakah Anda yakin ingin menghapus pegawai ini?")) {
        // Kirim permintaan hapus ke backend
        fetch(`backend/proses/proses_hapus_pegawai_ajax.php?id=${idPegawai}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("Data pegawai berhasil dihapus!");
                // Cari fungsi muat data/AJAX Anda untuk merefresh tabel secara otomatis
                // Contoh: loadTableData(); 
                location.reload(); // Atau reload halaman jika belum pakai AJAX penuh
            } else {
                alert("Gagal menghapus data: " + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>