<?php

session_start();

// 1. Jalur Koneksi Database
require_once 'backend/config/koneksi.php'; 

// Buat Object Database & ambil koneksinya
$db = new Database();
$conn = $db->conn;

// 2. JALUR AUTH
require_once 'backend/models/AuthModel.php'; 
require_once 'backend/controllers/AuthController.php'; 
require_once 'backend/middleware/AuthMiddleware.php'; 

// 3. Jalur Controller Fitur Baru
require_once 'backend/controllers/PegawaiController.php';
require_once 'backend/controllers/GajiController.php';

// 4. Inisialisasi Object Secara Bersih
$authModel = new AuthModel();
$authController = new AuthController($authModel);

$pegawaiController = new PegawaiController();
$gajiController = new GajiController();

$page = $_GET['page'] ?? 'login';

// =========================================================================
// 🛑 GERBANG AJAX UTAMAKAN (Paling Depan): Cegat Sebelum Masuk Dashboard Admin
// =========================================================================
if ($page === 'api_cari_pegawai' || $page === 'api_cari_cuti' || $page === 'api_proses_cuti_ajax' || isset($_GET['action_ajax'])) {
    AuthMiddleware::checkLogin();
    AuthMiddleware::checkAdmin();
    $koneksi_direct = $conn;

    // A. API CARI PEGAWAI
    if ($page === 'api_cari_pegawai' || ($_GET['action_ajax'] ?? '') === 'cari_pegawai') {
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
                        <a href="index.php?page=detail_pegawai&id=<?= $p['id_pegawai']; ?>" class="text-amber-500 hover:text-amber-700 font-medium inline-flex items-center gap-1">
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
        exit();
    }

    // B. API CARI PERSETUJUAN CUTI (FIXED: CASE SENSITIVE & ROUTING SAFE)
    if ($page === 'api_cari_cuti' || ($_GET['action_ajax'] ?? '') === 'cari_cuti') {
        $keyword = mysqli_real_escape_string($koneksi_direct, trim($_GET['keyword'] ?? ''));
        
        // Perbaikan: Loloskan data baik yang statusnya 'Pending' maupun 'pending'
        $where_clause = "WHERE (cuti.status = 'Pending' OR cuti.status = 'pending')";

        if (!empty($keyword)) {
            $where_clause .= " AND (pegawai.nama LIKE '%$keyword%' OR users.username LIKE '%$keyword%' OR cuti.alasan LIKE '%$keyword%')";
        }

        $query_ambil = mysqli_query($koneksi_direct, "
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

        if ($query_ambil && mysqli_num_rows($query_ambil) > 0) {
            while ($a = mysqli_fetch_assoc($query_ambil)) {
                ?>
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
                <?php
            }
        } else {
            echo '<tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">Data permohonan cuti tidak ditemukan.</td></tr>';
        }
        exit();
    }

    // C. API AKSI PROSES CUTI VIA AJAX
    if ($page === 'api_proses_cuti_ajax' || ($_GET['action_ajax'] ?? '') === 'proses_cuti') {
        $id_cuti = intval($_GET['id_cuti'] ?? 0);
        $aksi = $_GET['aksi_admin'] ?? ''; 
        $status_baru = ($aksi === 'setuju') ? 'disetujui' : 'ditolak';

        if ($id_cuti > 0 && ($aksi === 'setuju' || $aksi === 'tolak')) {
            if ($status_baru === 'disetujui') {
                $query_cuti = mysqli_query($koneksi_direct, "SELECT id_user, tanggal_mulai, tanggal_selesai FROM cuti WHERE id = '$id_cuti'");
                $data_cuti = mysqli_fetch_assoc($query_cuti);
                
                if ($data_cuti) {
                    $id_user_pegawai = $data_cuti['id_user'];
                    $tgl_mulai = new DateTime($data_cuti['tanggal_mulai']);
                    $tgl_selesai = new DateTime($data_cuti['tanggal_selesai']);
                    $durasi = $tgl_mulai->diff($tgl_selesai)->days + 1;

                    mysqli_query($koneksi_direct, "UPDATE pegawai SET sisa_cuti = sisa_cuti - $durasi WHERE id_user = '$id_user_pegawai'");
                }
            }
            $update = mysqli_query($koneksi_direct, "UPDATE cuti SET status = '$status_baru' WHERE id = '$id_cuti'");
            if ($update) {
                echo json_encode(["status" => "success", "message" => "Berhasil!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui database."]);
            }
        }
        exit();
    }
}

// 5. Struktur Routing Utama Aplikasi (Hanya untuk Halaman Penuh)
switch ($page) {

    case 'login':
        $authController->index(); 
        break;

    case 'register':
        $authController->register(); 
        break;

    case 'logout':
        $authController->logout();
        break;

    // ==========================================
    // 🔒 1. RUTE KHUSUS TEMPLATE ADMIN (FULL HTML PAGE)
    // ==========================================
    case 'dashboard_admin':
    case 'daftar_pegawai':
    case 'detail_pegawai':
    case 'tambah_pegawai':
    case 'absensi':          
    case 'rekap_absensi':    
    case 'gaji':             
    case 'cuti':             
    case 'persetujuan_cuti': 
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin(); 
        
        $koneksi = $conn; 
        require_once __DIR__ . '/frontend/dashboard/dashboard_admin.php'; 
        break;

    // ==========================================
    // 👤 2. RUTE KHUSUS TEMPLATE PEGAWAI BIASA
    // ==========================================
    case 'dashboard_pegawai':
    case 'form_absensi':     
    case 'form_cuti':        
    case 'riwayat_cuti':     
    case 'slip_gaji':        
        AuthMiddleware::checkLogin(); 
        $koneksi = $conn; 
        require_once __DIR__ . '/frontend/dashboard/dashboard.php'; 
        break;

    // ==========================================
    // ⚙️ 3. RUTE API & BACKEND PROSES DATA
    // ==========================================
    case 'pegawai':
        AuthMiddleware::checkLogin(); 
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            isset($_GET['id']) ? $pegawaiController->show($_GET['id']) : $pegawaiController->index(); 
        } elseif ($method === 'POST') {
            AuthMiddleware::checkAdmin(); $pegawaiController->store();
        } elseif ($method === 'PUT') {
            AuthMiddleware::checkAdmin(); $pegawaiController->update();
        } elseif ($method === 'DELETE') {
            AuthMiddleware::checkAdmin(); if (isset($_GET['id'])) $pegawaiController->destroy($_GET['id']);
        }
        break;

    case 'api_gaji':
        AuthMiddleware::checkLogin();
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            isset($_GET['id']) ? $gajiController->show($_GET['id']) : $gajiController->index(); 
        } elseif ($method === 'POST') {
            AuthMiddleware::checkAdmin(); $gajiController->store();
        } elseif ($method === 'DELETE') {
            AuthMiddleware::checkAdmin(); if (isset($_GET['id'])) $gajiController->destroy($_GET['id']);
        }
        break;

    case 'proses_absen':
        AuthMiddleware::checkLogin();
        $koneksi_db = $conn; 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['id_user'] ?? 0;
            $tanggal = date('Y-m-d');
            $jam_sekarang = date('H:i:s');
            $jenis = $_POST['jenis'] ?? '';

            $cek_absen = mysqli_query($koneksi_db, "SELECT * FROM absensi WHERE id_user = '$id_user' AND tanggal = '$tanggal'");
            $data_absen = mysqli_fetch_assoc($cek_absen);

            if ($jenis === 'masuk') {
                if (!$data_absen) {
                    mysqli_query($koneksi_db, "INSERT INTO absensi (id_user, tanggal, jam_masuk, status) VALUES ('$id_user', '$tanggal', '$jam_sekarang', 'Hadir')");
                }
            } elseif ($jenis === 'pulang') {
                if ($data_absen) {
                    mysqli_query($koneksi_db, "UPDATE absensi SET jam_pulang = '$jam_sekarang' WHERE id_user = '$id_user' AND tanggal = '$tanggal'");
                }
            }
        }
        
        header("Location: index.php?page=dashboard_pegawai");
        exit();
        break;

    case 'proses_ajukan_cuti':
        AuthMiddleware::checkLogin();
        $koneksi_db = $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['id_user'] ?? 0;
            $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
            $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
            $alasan = $_POST['alasan'] ?? '';
            
            $query_simpan = "INSERT INTO cuti (id_user, tanggal_mulai, tanggal_selesai, alasan, status) 
                             VALUES ('$id_user', '$tanggal_mulai', '$tanggal_selesai', '$alasan', 'Pending')";
            
            mysqli_query($koneksi_db, $query_simpan);
        }
        
        header("Location: index.php?page=riwayat_cuti");
        exit();
        break;

    case 'aksi_cuti':
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin(); 
        $koneksi_db = $conn;

        $id_cuti = $_GET['id'] ?? 0;
        $status_baru = $_GET['status'] ?? ''; 

        if ($id_cuti > 0 && ($status_baru === 'Disetujui' || $status_baru === 'Ditolak')) {
            if ($status_baru === 'Disetujui') {
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
        }

        header("Location: index.php?page=persetujuan_cuti");
        exit();
        break;

    case 'api_riwayat_pegawai':
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin();
        $koneksi_direct = $conn;
        
        $id_pegawai_db = mysqli_real_escape_string($koneksi_direct, $_GET['id'] ?? '');
        $query_riwayat = mysqli_query($koneksi_direct, "SELECT * FROM riwayat_pegawai WHERE id_pegawai = '$id_pegawai_db' ORDER BY id DESC");
        
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
        exit();
        break;

    default:
        http_response_code(404);
        echo json_encode(["status" => false, "message" => "404 - Page Not Found"]);
        break;
}