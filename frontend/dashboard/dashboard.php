<?php
// Pastikan middleware atau session sudah mengamankan halaman ini

/** @var mysqli $conn */
/** @var mysqli $koneksi */

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

// ==========================================
// 3. QUERY FETCH ABSENSI HARI INI SECARA REAL-TIME
// ==========================================
$hari_ini = date('Y-m-d');
$query_hari_ini = mysqli_query($koneksi_db, "SELECT * FROM absensi WHERE id_user = '$id_user' AND tanggal = '$hari_ini'");
$absen_hari_ini = mysqli_fetch_assoc($query_hari_ini);

// ==========================================
// 4. QUERY CEK DATA GAJI BULAN INI (SUPAYA TIDAK MERAH)
// ==========================================
// Query ini mengecek apakah admin sudah menginput data gaji untuk user ini pada bulan berjalan
$query_gaji = mysqli_query($koneksi_db, "SELECT COUNT(*) as total_gaji FROM gaji WHERE id_user = '$id_user'");
$data_gaji = mysqli_fetch_assoc($query_gaji);

// Jika jumlah datanya lebih dari 0, kita anggap sudah dibuat oleh admin
$gaji_sudah_ada = ($data_gaji['total_gaji'] ?? 0) > 0;
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
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-66 bg-slate-900 text-white flex flex-col justify-between hidden md:flex flex-shrink-0 border-r border-slate-800 shadow-xl">
            <div>
                <div class="h-20 flex items-center px-6 bg-slate-950/40 border-b border-slate-800/70">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-blue-600 rounded-xl text-white shadow-md shadow-blue-500/20">
                            <i class="fa-solid fa-id-card-alt text-lg"></i>
                        </div>
                        <span class="text-base font-black tracking-wider text-white uppercase">Simpeg Apps</span>
                    </div>
                </div>

                <div class="p-4 mx-3 my-4 bg-slate-950/30 rounded-2xl border border-slate-800/50 flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-base shadow-md">
                        <?php echo strtoupper(substr($nama, 0, 1)); ?>
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-bold text-slate-100 truncate"><?php echo ucwords($nama); ?></p>
                        <p class="text-[11px] text-blue-400 font-semibold tracking-wide uppercase mt-0.5"><?php echo $jabatan; ?></p>
                    </div>
                </div>

                <nav class="px-4 space-y-1.5 flex-1">
                    <a href="index.php?page=dashboard_pegawai" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all space-x-3 group">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-slate-400 group-hover:text-blue-400 transition-colors"></i> 
                        <span>Beranda</span>
                    </a>
                    
                    <div class="pt-2">
                        <a href="index.php?page=form_absensi" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all space-x-3 group">
                            <i class="fa-solid fa-clock w-5 text-center text-slate-400 group-hover:text-blue-400 transition-colors"></i> 
                            <span>Absensi</span>
                        </a>
                    </div>

                    <div class="pt-2">
                        <a href="index.php?page=form_cuti" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all space-x-3 group">
                            <i class="fa-solid fa-calendar-plus w-5 text-center text-slate-400 group-hover:text-blue-400 transition-colors"></i> 
                            <span>Ajukan Cuti</span>
                        </a>
                        <a href="index.php?page=riwayat_cuti" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all space-x-3 group">
                            <i class="fa-solid fa-history w-5 text-center text-slate-400 group-hover:text-blue-400 transition-colors"></i> 
                            <span>Riwayat Cuti</span>
                        </a>
                    </div>

                    <div class="pt-2">
                        <a href="index.php?page=slip_gaji" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all space-x-3 group">
                            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-slate-400 group-hover:text-blue-400 transition-colors"></i> 
                            <span>Slip Gaji</span>
                        </a>
                    </div>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800/60 bg-slate-950/20">
                <a href="index.php?page=logout" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-all space-x-3">
                    <i class="fa-solid fa-right-from-bracket text-center"></i> 
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <header class="h-20 bg-white border-b border-slate-200/80 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 hidden md:block tracking-tight">Selamat Datang, <?php echo ucwords($nama); ?> 👋</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs bg-emerald-50 text-emerald-700 font-bold border border-emerald-200/60 px-3 py-1.5 rounded-full flex items-center shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Sesi Aktif Pegawai
                    </span>
                    <div class="text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/40">
                        <i class="fa-solid fa-calendar text-slate-400 mr-1.5 text-xs"></i><?php echo date('d M Y'); ?>
                    </div>
                </div>
            </header>

            <main class="p-6 space-y-6 max-w-7xl w-full mx-auto">
                <?php
                $current_page = $_GET['page'] ?? 'dashboard_pegawai';

                switch ($current_page) {
                    case 'form_absensi':
                        include __DIR__ . '/../absensi/form_absensi.php';
                        break;
                    case 'rekap_absensi':
                        include __DIR__ . '/../absensi/rekap_absensi.php';
                        break;
                    case 'form_cuti':
                        include __DIR__ . '/../cuti/Form_Cuti.php';
                        break;
                    case 'persetujuan_cuti':
                        include __DIR__ . '/../cuti/Persetujuan_Cuti.php';
                        break;
                    case 'riwayat_cuti':
                        include __DIR__ . '/../cuti/Riwayat_Cuti.php';
                        break;
                    case 'slip_gaji':
                        include __DIR__ . '/../gaji/Slip_Gaji.php';
                        break;

                    case 'dashboard_pegawai':
                    default:
                        ?>
                        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-5 border border-slate-800">
                            <div>
                                <h2 class="text-2xl font-black tracking-tight">Rangkuman Aktivitas Kerja & Kompensasi</h2>
                                <p class="text-slate-300 text-sm mt-1 max-w-2xl font-medium">Pantau absensi harian, ajukan izin fasilitas kuota cuti tahunan, serta pantau status payroll gaji Anda secara komprehensif.</p>
                            </div>
                            <a href="index.php?page=form_absensi" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition-all shadow-lg shadow-indigo-600/20 flex items-center self-start md:self-auto whitespace-nowrap">
                                <i class="fa-solid fa-bolt mr-2 text-xs"></i> Akses Absensi Cepat
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <div onclick="window.location.href='index.php?page=form_absensi'" class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between group hover:shadow-md hover:border-blue-300 transition-all cursor-pointer">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-blue-500 transition-colors">Kehadiran Bulan Ini</p>
                                    <p class="text-3xl font-black text-slate-800"><?php echo $kehadiran; ?> <span class="text-xs font-normal text-slate-400">Hari Masuk</span></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                            </div>

                            <div onclick="window.location.href='index.php?page=riwayat_cuti'" class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between group hover:shadow-md hover:border-amber-300 transition-all cursor-pointer">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-amber-500 transition-colors">Sisa Kuota Cuti</p>
                                    <p class="text-3xl font-black text-slate-800"><?php echo $sisa_cuti; ?> <span class="text-xs font-normal text-slate-400">Hari Aktif</span></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-inner group-hover:bg-amber-500 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-calendar-minus"></i>
                                </div>
                            </div>

                            <div onclick="window.location.href='index.php?page=slip_gaji'" class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between group hover:shadow-md hover:border-emerald-300 transition-all cursor-pointer sm:col-span-2 lg:col-span-1">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-emerald-500 transition-colors">Status Gaji Terakhir</p>
                                    
                                    <?php if ($gaji_sudah_ada) : ?>
                                        <p class="text-base font-bold text-emerald-600 flex items-center mt-2.5 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full w-max text-xs">
                                            <i class="fa-solid fa-circle-check mr-1.5 text-[10px]"></i> Lunas Dibayar
                                        </p>
                                    <?php else : ?>
                                        <p class="text-base font-bold text-amber-600 flex items-center mt-2.5 bg-amber-50 border border-amber-100 px-3 py-1 rounded-full w-max text-xs animate-pulse">
                                            <i class="fa-solid fa-clock mr-1.5 text-[10px]"></i> Sedang Diproses
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                            </div>
                            
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <h3 class="font-bold text-slate-800 text-base">Status Absensi Hari Ini</h3>
                                <a href="index.php?page=rekap_absensi" class="text-xs text-indigo-600 font-bold hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100/70 px-3 py-1.5 rounded-xl transition-colors">
                                    <i class="fa-solid fa-eye mr-1 text-[10px]"></i> Lihat Semua Riwayat
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-500 whitespace-nowrap">
                                    <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200">
                                        <tr>
                                            <th class="px-6 py-4">Tanggal Kerja</th>
                                            <th class="px-6 py-4">Jam Absen Masuk</th>
                                            <th class="px-6 py-4">Jam Absen Pulang</th>
                                            <th class="px-6 py-4">Status Log</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4.5 flex items-center space-x-2">
                                                <i class="fa-solid fa-calendar-day text-slate-400"></i>
                                                <span><?php echo date('d M Y'); ?></span>
                                            </td>
                                            <td class="px-6 py-4.5 font-bold">
                                                <?= (!empty($absen_hari_ini['jam_masuk'])) ? '<span class="text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg"><i class="fa-solid fa-right-to-bracket text-emerald-500 mr-1.5 text-xs"></i>' . $absen_hari_ini['jam_masuk'] . '</span>' : '<span class="text-slate-300">-- : --</span>'; ?>
                                            </td>
                                            <td class="px-6 py-4.5 font-bold">
                                                <?= (!empty($absen_hari_ini['jam_pulang'])) ? '<span class="text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg"><i class="fa-solid fa-right-from-bracket text-amber-500 mr-1.5 text-xs"></i>' . $absen_hari_ini['jam_pulang'] . '</span>' : '<span class="text-slate-300">-- : --</span>'; ?>
                                            </td>
                                            <td class="px-6 py-4.5">
                                                <?php if (!$absen_hari_ini) : ?>
                                                    <span class="inline-flex items-center bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span> Belum Absen Masuk
                                                    </span>
                                                <?php elseif (empty($absen_hari_ini['jam_pulang'])) : ?>
                                                    <span class="inline-flex items-center bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-bold px-3 py-1 rounded-full shadow-sm animate-pulse">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span> Sudah Masuk, Belum Pulang
                                                    </span>
                                                <?php else : ?>
                                                    <span class="inline-flex items-center bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Presensi Selesai (Hadir)
                                                    </span>
                                                <?php endif; ?>
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