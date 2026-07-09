<?php
// Pastikan middleware atau session sudah mengamankan halaman ini

/** @var mysqli $conn */
/** @var mysqli $koneksi */

$koneksi_db = $conn ?? $koneksi; 
$id_user = $_SESSION['id_user'] ?? 0;
// ==========================================
// 1. FITUR FILTER BULAN & TAHUN
// ==========================================
$filter_bulan = $_GET['filter_bulan'] ?? date('m');
$filter_tahun = $_GET['filter_tahun'] ?? date('Y');

// Array pembantu konversi nama bulan Indonesia
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// Cek tipe struktur kolom tabel gaji Anda (apakah pakai kolom bulan/tahun terpisah, atau tanggal)
$query_check = mysqli_query($koneksi_db, "SHOW COLUMNS FROM gaji LIKE 'bulan'");
$has_month_column = mysqli_num_rows($query_check) > 0;

if ($has_month_column) {
    // Jalur A: Jika tabel gaji Anda menggunakan kolom 'bulan' dan 'tahun' terpisah
    $query_salary = mysqli_query($koneksi_db, "SELECT * FROM gaji WHERE id_user = '$id_user' AND bulan = '$filter_bulan' AND tahun = '$filter_tahun' LIMIT 1");
} else {
    // Jalur B: Jika tabel gaji Anda menggunakan kolom 'tanggal' (Tipe DATE)
    $query_salary = mysqli_query($koneksi_db, "SELECT * FROM gaji WHERE id_user = '$id_user' AND MONTH(tanggal) = '$filter_bulan' AND YEAR(tanggal) = '$filter_tahun' LIMIT 1");
}

$data_gaji = mysqli_fetch_assoc($query_salary);

// ==========================================
// 2. FITUR DETAIL RINCIAN PENDAPATAN & POTONGAN
// ==========================================
if ($data_gaji) {
    $gaji_pokok = $data_gaji['gaji_pokok'] ?? 0;
    $tunjangan = $data_gaji['tunjangan'] ?? 0;
    $bonus = $data_gaji['bonus'] ?? 0;
    $potongan = $data_gaji['potongan'] ?? 0;
    
    $total_pendapatan = $gaji_pokok + $tunjangan + $bonus;
    $gaji_bersih = $total_pendapatan - $potongan;
    
    $status_gajian = true;
} else {
    $gaji_pokok = 0; $tunjangan = 0; $bonus = 0; $potongan = 0;
    $total_pendapatan = 0; $gaji_bersih = 0;
    $status_gajian = false;
}
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Slip Gaji Digital Pegawai</h2>
            <p class="text-slate-400 text-xs font-semibold mt-0.5">Lihat rincian kompensasi bulanan, lakukan pencarian arsip, serta unduh dokumen resmi.</p>
        </div>
        
        <form method="GET" action="index.php" class="flex flex-wrap items-center gap-2.5">
            <input type="hidden" name="page" value="slip_gaji">
            
            <select name="filter_bulan" class="bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-indigo-500 transition-all cursor-pointer">
                <?php foreach ($nama_bulan as $code => $name) : ?>
                    <option value="<?= $code; ?>" <?= ($filter_bulan == $code) ? 'selected' : ''; ?>><?= $name; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="filter_tahun" class="bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-indigo-500 transition-all cursor-pointer">
                <?php 
                $tahun_sekarang = date('Y');
                for ($t = $tahun_sekarang; $t >= $tahun_sekarang - 3; $t--) : ?>
                    <option value="<?= $t; ?>" <?= ($filter_tahun == $t) ? 'selected' : ''; ?>><?= $t; ?></option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/10 transition-all flex items-center">
                <i class="fa-solid fa-filter mr-1.5 text-[10px]"></i> Filter Data
            </button>
        </form>
    </div>

    <?php if ($status_gajian) : ?>
        <div id="printArea" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden border-t-4 border-indigo-600">
            
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/40">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">SLIP GAJI RESMI (DIGITAL)</h3>
                        <p class="text-xs font-bold text-indigo-600 tracking-wide uppercase mt-0.5">Periode: <?= $nama_bulan[$filter_bulan] . ' ' . $filter_tahun; ?></p>
                    </div>
                </div>
                
                <button onclick="window.print()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-sm flex items-center print:hidden">
                    <i class="fa-solid fa-print mr-2 text-xs"></i> Cetak / Unduh PDF
                </button>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 1. Rincian Pendapatan (+)
                    </h4>
                    
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-3.5 font-semibold text-sm text-slate-700">
                        <div class="flex justify-between items-center border-b border-slate-200/50 pb-2.5">
                            <span class="text-slate-500">Gaji Pokok</span>
                            <span class="text-slate-900 font-bold">Rp <?= number_format($gaji_pokok, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-200/50 pb-2.5">
                            <span class="text-slate-500">Tunjangan Jabatan</span>
                            <span class="text-slate-900 font-bold">Rp <?= number_format($tunjangan, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-1">
                            <span class="text-slate-500">Bonus / Insentif Kerja</span>
                            <span class="text-slate-900 font-bold">Rp <?= number_format($bonus, 0, ',', '.'); ?></span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-slate-200 text-emerald-700 bg-emerald-50/50 px-3 py-2 rounded-xl">
                            <span class="text-xs font-bold uppercase tracking-wider">Subtotal Pendapatan</span>
                            <span class="font-black text-base">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center">
                        <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> 2. Rincian Potongan (-)
                    </h4>
                    
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-3.5 font-semibold text-sm text-slate-700">
                        <div class="flex justify-between items-center border-b border-slate-200/50 pb-2.5">
                            <span class="text-slate-500">Potongan Absensi / Kedisiplinan</span>
                            <span class="text-rose-600 font-bold">- Rp <?= number_format($potongan, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-1 text-slate-300 select-none">
                            <span>-</span><span>-</span>
                        </div>
                        <div class="flex justify-between items-center pb-1 text-slate-300 select-none">
                            <span>-</span><span>-</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-slate-200 text-rose-700 bg-rose-50/50 px-3 py-2 rounded-xl">
                            <span class="text-xs font-bold uppercase tracking-wider">Subtotal Potongan</span>
                            <span class="font-black text-base">- Rp <?= number_format($potongan, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mx-6 md:mx-8 mb-8 p-6 bg-gradient-to-br from-indigo-900 to-slate-950 text-white rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-md">
                <div>
                    <h4 class="text-base font-black tracking-tight">TOTAL GAJI BERSIH (TAKE HOME PAY)</h4>
                    <p class="text-indigo-300 text-xs font-medium mt-0.5">Dana bersih yang ditransfer resmi ke rekening Anda.</p>
                </div>
                <div class="text-2xl md:text-3xl font-black text-emerald-400 tracking-tight self-end sm:self-auto">
                    Rp <?= number_format($gaji_bersih, 0, ',', '.'); ?>
                </div>
            </div>
            
            <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/60 text-[11px] text-slate-400 font-medium text-center">
                <i class="fa-solid fa-shield-halved mr-1 text-indigo-500"></i> Dokumen ini sah dikeluarkan oleh Sistem Informasi Manajemen Kepegawaian (SIMPEG) secara digital.
            </div>
        </div>
    <?php else : ?>
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-12 text-center max-w-xl mx-auto space-y-4">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mx-auto border border-amber-100 shadow-inner">
                <i class="fa-solid fa-clock-rotate-left animate-spin" style="animation-duration: 4s;"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-800 text-base">Slip Gaji Belum Tersada</h3>
                <p class="text-slate-400 text-xs font-semibold px-4">Admin belum memproses atau menginput rincian payroll Anda pada periode bulan <span class="text-slate-700 font-bold"><?= $nama_bulan[$filter_bulan] . ' ' . $filter_tahun; ?></span>.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printArea, #printArea * {
        visibility: visible;
    }
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>