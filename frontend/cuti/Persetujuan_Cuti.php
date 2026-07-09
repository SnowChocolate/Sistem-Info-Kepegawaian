<?php
// ================================================================
// ⚙️ FITUR: LOGIKA VALIDASI ADMIN & AJAX SEARCH SATU TEMPAT
// ================================================================
/** @var mysqli $koneksi */
$koneksi_db = $koneksi;

// 1. Logika Aksi Setuju / Tolak Cuti
if (isset($_GET['aksi_admin']) && isset($_GET['id_cuti'])) {
    $id_cuti = intval($_GET['id_cuti']);
    $aksi = $_GET['aksi_admin']; 
    $status_baru = ($aksi === 'setuju') ? 'disetujui' : 'ditolak';

    if ($status_baru === 'disetujui') {
        $query_cuti = mysqli_query($koneksi_db, "SELECT id_user, tanggal_mulai, tanggal_selesai FROM cuti WHERE id = '$id_cuti'");
        $data_cuti = mysqli_fetch_assoc($query_cuti);
        
        if ($data_cuti) {
            $id_user_pegawai = $data_cuti['id_user'];
            $tgl_mulai = new DateTime($data_cuti['tanggal_mulai']);
            $tgl_selesai = new DateTime($data_cuti['tanggal_selesai']);
            $durasi = $tgl_mulai->diff($tgl_selesai)->days + 1;

            mysqli_query($koneksi_db, "UPDATE pegawai SET sisa_cuti = sisa_cuti - $durasi WHERE id_user = '$id_user_pegawai'");
        }
    }

    mysqli_query($koneksi_db, "UPDATE cuti SET status = '$status_baru' WHERE id = '$id_cuti'");
    echo "<script>alert('Pengajuan cuti berhasil di-$status_baru!'); window.location.href = 'index.php?page=persetujuan_cuti';</script>";
    exit();
}

// 2. Ambil Keyword Pencarian (Bisa dari ketikan AJAX)
$keyword = mysqli_real_escape_string($koneksi_db, trim($_GET['keyword'] ?? ''));
$where_clause = "WHERE LOWER(cuti.status) = 'pending'";

if (!empty($keyword)) {
    $where_clause .= " AND (pegawai.nama LIKE '%$keyword%' OR users.username LIKE '%$keyword%' OR cuti.alasan LIKE '%$keyword%')";
}

// 3. Query Utama Penarik Data
$query_ambil = mysqli_query($koneksi_db, "
    SELECT 
        cuti.id, 
        cuti.tanggal_mulai AS tgl_mulai, 
        cuti.tanggal_selesai AS tgl_selesai, 
        cuti.alasan, 
        COALESCE(pegawai.nama, users.username, 'Pegawai') AS nama_pegawai 
    FROM cuti 
    LEFT JOIN pegawai ON cuti.id_user = pegawai.id_user
    LEFT JOIN users ON cuti.id_user = users.id
    $where_clause
    ORDER BY cuti.id DESC
");

$data_approval = [];
if ($query_ambil) {
    while ($row = mysqli_fetch_assoc($query_ambil)) {
        $data_approval[] = $row;
    }
}

// 🟢 JIKA REQUEST DATANG DARI FETCH AJAX: Hanya cetak isi <tr> nya saja, lalu stop!
if (isset($_GET['action_ajax']) && $_GET['action_ajax'] === 'cari_cuti') {
    if (!empty($data_approval)) {
        foreach ($data_approval as $a) {
            ?>
            <tr data-id="<?= $a['id']; ?>">
                <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($a['nama_pegawai']); ?></td>
                <td class="px-6 py-4 text-gray-600"><?= date('d M Y', strtotime($a['tgl_mulai'])); ?> s/d <?= date('d M Y', strtotime($a['tgl_selesai'])); ?></td>
                <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($a['alasan']); ?></td>
                <td class="px-6 py-4 space-x-2">
                    <button data-id="<?= $a['id']; ?>" data-aksi="setuju" data-pesan="Apakah Anda yakin ingin MENYETUJUI permohonan cuti ini?"
                       class="tombol-proses-cuti px-3 py-1 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700 transition-colors">Setujui</button>
                    <button data-id="<?= $a['id']; ?>" data-aksi="tolak" data-pesan="Apakah Anda yakin ingin MENOLAK permohonan cuti ini?"
                       class="tombol-proses-cuti px-3 py-1 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700 transition-colors">Tolak</button>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr id="baris-kosong"><td colspan="4" class="px-6 py-10 text-center text-gray-400">Data permohonan cuti tidak ditemukan.</td></tr>';
    }
    exit(); // Menghentikan rendering HTML sisa agar layout admin tidak bocor
}
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Persetujuan Cuti Pegawai</h1>
        <p class="text-sm text-gray-600">Verifikasi berkas permintaan cuti dari staff internal.</p>
    </div>
    
    <div class="w-full sm:w-auto">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="input-cari-cuti" placeholder="Cari nama pegawai / alasan..." 
                   class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800 w-full sm:w-64">
        </div>
    </div>
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
        <tbody id="wadah-tabel-cuti" class="divide-y divide-gray-100 text-sm">
            <?php if(!empty($data_approval)): foreach($data_approval as $a): ?>
            <tr data-id="<?= $a['id']; ?>" class="transition-all duration-300">
                <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($a['nama_pegawai']); ?></td>
                <td class="px-6 py-4 text-gray-600"><?= date('d M Y', strtotime($a['tgl_mulai'])); ?> s/d <?= date('d M Y', strtotime($a['tgl_selesai'])); ?></td>
                <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($a['alasan']); ?></td>
                <td class="px-6 py-4 space-x-2">
                    <button data-id="<?= $a['id']; ?>" data-aksi="setuju" data-pesan="Apakah Anda yakin ingin MENYETUJUI permohonan cuti ini?"
                       class="tombol-proses-cuti px-3 py-1 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700 transition-colors">Setujui</button>
                    <button data-id="<?= $a['id']; ?>" data-aksi="tolak" data-pesan="Apakah Anda yakin ingin MENOLAK permohonan cuti ini?"
                       class="tombol-proses-cuti px-3 py-1 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700 transition-colors">Tolak</button>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr id="baris-kosong">
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada permohonan cuti masuk yang perlu diproses.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const wadahTabel = document.getElementById("wadah-tabel-cuti");
    const inputCari = document.getElementById("input-cari-cuti");
    const HTMLDataAwal = wadahTabel.innerHTML;

    // AJAX LIVE SEARCH DATABASE 
    inputCari.addEventListener("keyup", function () {
        const keyword = this.value.trim();

        if (keyword === "") {
            wadahTabel.innerHTML = HTMLDataAwal;
            return;
        }
        
        // Menembak halaman persetujuan_cuti itu sendiri dengan parameter ajax tambahan
        fetch(`index.php?page=persetujuan_cuti&action_ajax=cari_cuti&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.text())
            .then(html => {
                // Langsung masukkan response html murni ke dalam wadah tabel tanpa ribet DOMParser
                wadahTabel.innerHTML = html;
            })
            .catch(err => console.error(err));
    });

    // PROSES TOMBOL SETUJU / TOLAK REDIRECT 
    wadahTabel.addEventListener("click", function (e) {
        if (e.target.classList.contains("tombol-proses-cuti")) {
            const tombol = e.target;
            const idCuti = tombol.getAttribute("data-id");
            const aksi = tombol.getAttribute("data-aksi");
            const pesanKonfirmasi = tombol.getAttribute("data-pesan");

            if (confirm(pesanKonfirmasi)) {
                window.location.href = `index.php?page=persetujuan_cuti&aksi_admin=${aksi}&id_cuti=${idCuti}`;
            }
        }
    });
});
</script>