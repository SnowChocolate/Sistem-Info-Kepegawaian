<?php

session_start();

// 1. Jalur Koneksi Database
require_once 'backend/config/koneksi.php'; 

// Buat Object Database & ambil koneksinya
$db = new Database();
$conn = $db->conn;

// 2. JALUR AUTH (Sesuai Struktur Folder Proyekmu)
require_once 'backend/models/AuthModel.php'; 
require_once 'backend/controllers/AuthController.php'; 
require_once 'backend/middleware/AuthMiddleware.php'; 

// 3. Jalur Controller Fitur Baru
require_once 'backend/controllers/PegawaiController.php';
require_once 'backend/controllers/GajiController.php';

// 4. Inisialisasi Object Secara Bersih (Tidak Berulang)
$authModel = new AuthModel();
$authController = new AuthController($authModel); // Dikirim ke constructor agar tidak infinite loop

$pegawaiController = new PegawaiController();
$gajiController = new GajiController();

$page = $_GET['page'] ?? 'login';

// 5. Struktur Routing Aplikasi
switch ($page) {

    case 'login':
        $authController->index(); 
        break;

    case 'register':
        $authController->register(); // Memanggil fungsi registrasi baru
        break;

    case 'logout':
        $authController->logout();
        break;

case 'dashboard_admin':
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin();
        
        require_once __DIR__ . '/frontend/dashboard/dashboard_admin.php'; 
        break;

   // RUTE UNTUK DASHBOARD PEGAWAI BIASA (Hanya fitur mandiri)
    case 'dashboard_pegawai':
    case 'form_absensi':
    case 'rekap_absensi':
    case 'form_cuti':
    case 'persetujuan_cuti':
    case 'riwayat_cuti':
    case 'slip_gaji':
        AuthMiddleware::checkLogin(); 
        $koneksi = $conn; 
        require_once __DIR__ . '/frontend/dashboard/dashboard.php'; 
        break;

    // 🔐 RUTE KHUSUS MANAJEMEN PEGAWAI (HANYA UNTUK ADMIN)
    case 'daftar_pegawai':
    case 'detail_pegawai':
    case 'tambah_pegawai':
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin(); // 🔥 Pegawai biasa otomatis ditolak di sini
        
        $koneksi = $conn; 
        // Memuat halaman lewat template admin, bukan dashboard pegawai
        require_once __DIR__ . '/frontend/dashboard/dashboard_admin.php'; 
        break;
    // ==========================================
    // RUTE API UNTUK PEGAWAI / KARYAWAN
    // ==========================================
    case 'pegawai':
        AuthMiddleware::checkLogin(); 
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $pegawaiController->show($_GET['id']); 
            } else {
                $pegawaiController->index(); 
            }
        } elseif ($method === 'POST') {
            AuthMiddleware::checkAdmin(); 
            $pegawaiController->store();
        } elseif ($method === 'PUT') {
            AuthMiddleware::checkAdmin(); 
            $pegawaiController->update();
        } elseif ($method === 'DELETE') {
            AuthMiddleware::checkAdmin(); 
            if (isset($_GET['id'])) {
                $pegawaiController->destroy($_GET['id']);
            }
        }
        break;

    // ==========================================
    // RUTE API UNTUK REKAPAN GAJI
    // ==========================================
    case 'gaji':
        AuthMiddleware::checkLogin();
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $gajiController->show($_GET['id']); 
            } else {
                $gajiController->index(); 
            }
        } elseif ($method === 'POST') {
            AuthMiddleware::checkAdmin(); 
            $gajiController->store();
        } elseif ($method === 'DELETE') {
            AuthMiddleware::checkAdmin(); 
            if (isset($_GET['id'])) {
                $gajiController->destroy($_GET['id']);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "status" => false,
            "message" => "404 - Page Not Found"
        ]);
        break;
}