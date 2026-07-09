<?php
// ================================================================
// 📊 FITUR: AMBIL DATA RIWAYAT CUTI PEGAWAI YANG LOGIN
// ================================================================
/** @var mysqli $koneksi */
$koneksi_db = $koneksi;

$id_user = $_SESSION['id_user'] ?? 0;

// 1. Ambil data sisa cuti langsung dari tabel pegawai untuk ringkasan info
$query_pegawai = mysqli_query($koneksi_db, "SELECT sisa_cuti FROM pegawai WHERE id_user = '$id_user'");
$data_pegawai = mysqli_fetch_assoc($query_pegawai);
$sisa_jatah = $data_pegawai['sisa_cuti'] ?? 0;

// 2. Ambil semua data cuti milik pegawai ini
$query_riwayat = mysqli_query($koneksi_db, "
    SELECT tanggal_mulai, tanggal_selesai, alasan, status 
    FROM cuti 
    WHERE id_user = '$id_user' 
    ORDER BY id DESC
");

// Hitung ringkasan status untuk kartu informasi
$total_pending = 0;
$total_disetujui = 0;
$list_cuti = [];

if ($query_riwayat) {
    while ($row = mysqli_fetch_assoc($query_riwayat)) {
        $list_cuti[] = $row;
        if ($row['status'] === 'pending') $total_pending++;
        if ($row['status'] === 'disetujui') $total_disetujui++;
    }
}
?>

<div class="space-y-6 max-w-6xl animate-fade-in">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Pengajuan Cuti</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau status verifikasi dan rekam jejak izin ketidakhadiran Anda.</p>
        </div>
        <div>
            <a href="index.php?page=form_cuti" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition-all space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Ajukan Cuti Baru</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 text-white shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wider">Sisa Jatah Cuti</p>
                <h3 class="text-3xl font-black mt-1"><?= $sisa_jatah; ?> <span class="text-sm font-normal text-indigo-200">Hari</span></h3>
            </div>
            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm"><i class="fa-solid fa-calendar-check text-2xl"></i></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Diproses</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $total_pending; ?> <span class="text-sm font-normal text-slate-500">Berkas</span></h3>
            </div>
            <div class="p-3 bg-amber-50 text-amber-500 rounded-xl"><i class="fa-solid fa-hourglass-half text-2xl"></i></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Disetujui</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $total_disetujui; ?> <span class="text-sm font-normal text-slate-500">Kali</span></h3>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-500 rounded-xl"><i class="fa-solid fa-circle-check text-2xl"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-xs font-bold text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Durasi Tanggal</th>
                        <th class="px-6 py-4">Lama Cuti</th>
                        <th class="px-6 py-4">Alasan Keterangan</th>
                        <th class="px-6 py-4 text-center">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($list_cuti)) : ?>
                        <?php foreach ($list_cuti as $row) : 
                            // Menghitung selisih hari
                            $tgl1 = new DateTime($row['tanggal_mulai']);
                            $tgl2 = new DateTime($row['tanggal_selesai']);
                            $durasi_hari = $tgl1->diff($tgl2)->days + 1;
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4.5">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-slate-100 text-slate-600 rounded-lg group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <i class="fa-solid fa-calendar-days text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-800"><?= date('d M Y', strtotime($row['tanggal_mulai'])); ?></span>
                                        <span class="text-xs text-slate-400 block mt-0.5">s/d <?= date('d M Y', strtotime($row['tanggal_selesai'])); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    <?= $durasi_hari; ?> Hari
                                </span>
                            </td>
                            <td class="px-6 py-4.5 max-w-xs truncate">
                                <span class="text-slate-600 font-medium" title="<?= $row['alasan']; ?>"><?= $row['alasan']; ?></span>
                            </td>
                            <td class="px-6 py-4.5 text-center">
                                <?php if ($row['status'] === 'pending') : ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold border border-amber-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 mr-2 bg-amber-500 rounded-full animate-pulse"></span>
                                        Pending
                                    </span>
                                <?php elseif ($row['status'] === 'disetujui') : ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 mr-2 bg-emerald-500 rounded-full"></span>
                                        Disetujui
                                    </span>
                                <?php else : ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 mr-2 bg-rose-500 rounded-full"></span>
                                        Ditolak
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="p-4 bg-slate-50 text-slate-300 rounded-full">
                                    <i class="fa-solid fa-receipt text-4xl"></i>
                                </div>
                                <div class="text-sm font-medium text-slate-500">Belum ada riwayat pengajuan cuti</div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>