<?php
// Pastikan middleware atau session sudah mengamankan halaman ini

/** @var mysqli $conn */
/** @var mysqli $koneksi */
// Trik di atas memberi tahu VS Code bahwa variabel ini datang dari file lain dan bertipe data mysqli

$koneksi_db = $conn ?? $koneksi; 

$id_user = $_SESSION['id_user'] ?? 0;
$username = $_SESSION['username'] ?? 'Pegawai';

// ==========================================
// 1. QUERY AMBIL DATA PEGAWAI
// ==========================================
$query_pegawai = mysqli_query($koneksi_db, "SELECT * FROM pegawai WHERE id_user = '$id_user'");
$data_pegawai = mysqli_fetch_assoc($query_pegawai);

$nama = $data_pegawai['nama'] ?? $username;
$jabatan = $data_pegawai['jabatan'] ?? 'Staf Kepegawaian';
$sisa_cuti = $data_pegawai['sisa_cuti'] ?? 12; 

// ==========================================
// 2. QUERY RINGKASAN ABSENSI BULAN INI
// ==========================================
$bulan_ini = date('m');
$tahun_ini = date('Y');
$query_absen = mysqli_query($koneksi_db, "SELECT COUNT(*) as total FROM absensi WHERE id_user = '$id_user' AND MONTH(tanggal) = '$bulan_ini' AND YEAR(tanggal) = '$tahun_ini'");
$data_absen = mysqli_fetch_assoc($query_absen);
$kehadiran = $data_absen['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEG - Dashboard Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex flex-shrink-0">
            <div>
                <div class="h-16 flex items-center justify-center bg-slate-950 px-6 border-b border-slate-800">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-id-card-alt text-blue-400 text-2xl"></i>
                        <span class="text-lg font-bold tracking-wider">SIMPEG APPS</span>
                    </div>
                </div>

                <div class="p-4 border-b border-slate-800 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-inner">
                        <?php echo strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-200"><?php echo ucwords($_SESSION['username'] ?? 'Pegawai'); ?></p>
                        <p class="text-xs text-blue-400 font-medium">Staf Kepegawaian</p>
                    </div>
                </div>

                <nav class="mt-4 px-3 space-y-1 flex-1">
    
    <a href="index.php?page=dashboard_pegawai" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
        <i class="fa-solid fa-chart-pie mr-3 w-5 text-center"></i> Dashboard Beranda
    </a>
    
    <div class="space-y-1">
        <span class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider block mt-4 mb-1">Absensi</span>
        
        <a href="index.php?page=form_absensi" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-clock mr-3 w-5 text-center"></i> Isi Absen Masuk/Pulang
        </a>
        
        <a href="index.php?page=rekap_absensi" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-list-check mr-3 w-5 text-center"></i> Rekap Absensi
        </a>
    </div>

    <div class="space-y-1">
        <span class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider block mt-4 mb-1">Fasilitas Cuti</span>
        
        <a href="index.php?page=form_cuti" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-calendar-plus mr-3 w-5 text-center"></i> Ajukan Cuti Baru
        </a>
        
        <a href="index.php?page=riwayat_cuti" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-history mr-3 w-5 text-center"></i> Riwayat Cuti Anda
        </a>
    </div>

    <div class="space-y-1">
        <span class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider block mt-4 mb-1">Keuangan</span>
        
        <a href="index.php?page=slip_gaji" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-file-invoice-dollar mr-3 w-5 text-center"></i> Slip Gaji
        </a>
    </div>

    <div class="space-y-1">
        <span class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider block mt-4 mb-1">Manajemen SDM</span>
        
        <a href="index.php?page=daftar_pegawai" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
            <i class="fa-solid fa-users mr-3 w-5 text-center"></i> Daftar Semua Pegawai
        </a>
    </div>

</nav>
</div>

            <div class="p-3 border-t border-slate-800">
                <a href="index.php?page=logout" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-3 text-lg w-5 text-center"></i> Keluar
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm">
                <div class="flex items-center space-x-2">
                    <h1 class="text-xl font-bold text-gray-800 hidden md:block">Selamat Datang, <?php echo ucwords($_SESSION['username'] ?? 'Pegawai'); ?> 👋</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Sesi Aktif
                    </span>
                    <div class="text-sm font-medium text-gray-500">
                        <?php echo date('d M Y'); ?>
                    </div>
                </div>
            </header>

            <!-- 📊 ISI KONTEN UTAMA SECARA DINAMIS -->
            <main class="p-6 space-y-6 max-w-7xl w-full mx-auto">
                <?php
                // Ambil halaman aktif saat ini dari URL, defaultnya adalah dashboard_pegawai
                $current_page = $_GET['page'] ?? 'dashboard_pegawai';

                switch ($current_page) {
                    // --- SUB-MENU ABSENSI ---
                    case 'form_absensi':
                        include __DIR__ . '/../absensi/form_absensi.php';
                        break;
                    case 'rekap_absensi':
                        include __DIR__ . '/../absensi/rekap_absensi.php';
                        break;

                    // --- SUB-MENU CUTI ---
                    case 'form_cuti':
                        include __DIR__ . '/../cuti/Form_Cuti.php';
                        break;
                    case 'persetujuan_cuti':
                        include __DIR__ . '/../cuti/Persetujuan_Cuti.php';
                        break;
                    case 'riwayat_cuti':
                        include __DIR__ . '/../cuti/Riwayat_Cuti.php';
                        break;

                    // --- SUB-MENU DATA PEGAWAI ---
                    case 'daftar_pegawai':
                        include __DIR__ . '/../pegawai/Daftar_Pegawai.php';
                        break;
                    case 'detail_pegawai':
                        include __DIR__ . '/../pegawai/Detail_Pegawai.php';
                        break;
                    case 'tambah_pegawai':
                        include __DIR__ . '/../pegawai/Tambah_Pegawai.php';
                        break;

                    // --- SUB-MENU GAJI ---
                    case 'slip_gaji':
                        include __DIR__ . '/../gaji/Slip_Gaji.php';
                        break;

                    // --- TAMPILAN BERANDA UTAMA (DEFAULT) ---
                    case 'dashboard_pegawai':
                    default:
                        ?>
                        <!-- BANNER NOTIFIKASI INFORMASI -->
                        <div class="bg-gradient-to-r bg-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold">Rangkuman Aktivitas Kerja & Kompensasi</h2>
                                <p class="text-slate-300 text-sm mt-1">Pantau kehadiran, kuota cuti, dan status pembayaran gaji Anda secara real-time.</p>
                            </div>
                            <a href="index.php?page=form_absensi" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow-md flex items-center">
                                <i class="fa-solid fa-bolt mr-2"></i> Akses Fitur Cepat
                            </a>
                        </div>

                        <!-- 🃏 KARTU STATISTIK (METRIK UTAMA) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Kartu Kehadiran -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Kehadiran Bulan Ini</p>
                                    <p class="text-3xl font-extrabold text-gray-900">0 <span class="text-sm font-normal text-gray-500">Hari</span></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                            </div>

                            <!-- Kartu Sisa Cuti -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Sisa Kuota Cuti</p>
                                    <p class="text-3xl font-extrabold text-gray-900">12 <span class="text-sm font-normal text-gray-500">Hari</span></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                                    <i class="fa-solid fa-calendar-minus"></i>
                                </div>
                            </div>

                            <!-- Kartu Status Gaji -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between sm:col-span-2 lg:col-span-1">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Gaji Bulan Ini</p>
                                    <p class="text-lg font-bold text-emerald-600 flex items-center mt-1">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-sm"></i> Sudah Dibayar
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                            </div>
                        </div>

                        <!-- 📋 TABEL RIWAYAT AKTIVITAS -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="font-bold text-gray-800 text-lg">Log Absensi Terakhir Anda</h3>
                                <a href="index.php?page=rekap_absensi" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-500">
                                    <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                        <tr>
                                            <th class="px-6 py-3">Tanggal</th>
                                            <th class="px-6 py-3">Jam Masuk</th>
                                            <th class="px-6 py-3">Jam Pulang</th>
                                            <th class="px-6 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            <td class="px-6 py-4"><?php echo date('d M Y'); ?></td>
                                            <td class="px-6 py-4 text-gray-400">-- : --</td>
                                            <td class="px-6 py-4 text-gray-400">-- : --</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-rose-50 text-rose-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Belum Absen</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        break;
                }
                ?>
            </main>
        </div>
    </div>

</body>
</html>