<?php
// ================================================================
// ⚙️ FITUR: LOGIKA PEMROSESAN SIMPAN PENGAJUAN CUTI PEGAWAI
// ================================================================
/** @var mysqli $koneksi */
$koneksi_db = $koneksi; 

$id_user = $_SESSION['id_user'] ?? 0;

// Ambil jatah sisa cuti saat ini untuk ditampilkan sebagai info di form
$query_kuota = mysqli_query($koneksi_db, "SELECT sisa_cuti FROM pegawai WHERE id_user = '$id_user'");
$data_kuota = mysqli_fetch_assoc($query_kuota);
$sisa_jatah = $data_kuota['sisa_cuti'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_cuti'])) {
    $tgl_mulai = $_POST['tgl_mulai'] ?? '';
    $tgl_selesai = $_POST['tgl_selesai'] ?? '';
    $alasan = $_POST['alasan'] ?? '';
    
    if (!empty($tgl_mulai) && !empty($tgl_selesai) && !empty($alasan)) {
        // Status diset ke 'pending' (huruf kecil) agar sesuai dengan ENUM database
        $query_simpan = "INSERT INTO cuti (id_user, tanggal_mulai, tanggal_selesai, alasan, status) 
                         VALUES ('$id_user', '$tgl_mulai', '$tgl_selesai', '$alasan', 'pending')";
        
        if (mysqli_query($koneksi_db, $query_simpan)) {
            echo "<script>
                    alert('Pengajuan cuti berhasil dikirim ke Admin!');
                    window.location.href = 'index.php?page=riwayat_cuti';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan pengajuan cuti.');</script>";
        }
    }
}
?>

<div class="space-y-6 max-w-5xl animate-fade-in">
    
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Pengajuan Cuti</h1>
        <p class="text-sm text-slate-500 mt-1">Isi detail permohonan izin cuti kerja Anda dengan data yang valid.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <form action="" method="POST" class="space-y-5">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Mulai Cuti</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-calendar-plus text-sm"></i>
                            </div>
                            <input type="date" name="tgl_mulai" required 
                                   class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Selesai Cuti</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                            </div>
                            <input type="date" name="tgl_selesai" required 
                                   class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Alasan / Keterangan Cuti</label>
                    <div class="relative rounded-xl shadow-sm">
                        <textarea name="alasan" rows="4" required 
                                  placeholder="Contoh: Menghadiri urusan pernikahan keluarga inti di luar kota..."
                                  class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3 border-t border-slate-100 mt-4">
                    <a href="index.php?page=riwayat_cuti" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" name="kirim_cuti" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition-all space-x-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Permohonan</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-5 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Jatah Cuti Aktif</p>
                    <h3 class="text-3xl font-black mt-1"><?= $sisa_jatah; ?> <span class="text-xs font-medium text-slate-400">Hari Sisa</span></h3>
                </div>
                <div class="p-3 bg-white/5 border border-white/10 rounded-xl">
                    <i class="fa-solid fa-hourglass-start text-indigo-400 text-xl"></i>
                </div>
            </div>

            <div class="bg-amber-50/60 border border-amber-200/70 p-4 rounded-2xl text-amber-900">
                <div class="flex space-x-3">
                    <i class="fa-solid fa-circle-info text-amber-500 text-base mt-0.5"></i>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider">Ketentuan Cuti:</h4>
                        <ul class="text-xs space-y-1.5 mt-2 text-amber-800 list-disc list-inside">
                            <li>Pastikan tanggal pengajuan tidak bertabrakan dengan jadwal absensi aktif.</li>
                            <li>Setiap permohonan yang dikirim akan berstatus <span class="font-bold">Pending</span> menunggu review admin.</li>
                            <li>Jika disetujui, jatah cuti tahunan Anda akan berkurang otomatis sesuai durasi hari.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>