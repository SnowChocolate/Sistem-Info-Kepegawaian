<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'koneksi.php';
/** @var mysqli $koneksi */

$query_pegawai = "SELECT COUNT(*) as total FROM pegawai";
$result_pegawai = mysqli_query($koneksi, $query_pegawai);
$total_pegawai = ($result_pegawai) ? mysqli_fetch_assoc($result_pegawai)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SIMPEG</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        /* Style Navbar */
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #343a40; padding: 10px 20px; color: #fff; }
        .nav-links { display: flex; gap: 15px; }
        .nav-links a { color: #fff; text-decoration: none; padding: 8px 12px; border-radius: 4px; }
        .nav-links a:hover, .nav-links a.active { background: #007bff; }
        .btn-logout { color: #ffc107; text-decoration: none; font-weight: bold; }
        
        .container { padding: 20px; }
        .grid { display: flex; gap: 20px; margin-top: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex: 1; border-top: 4px solid #007bff; }
        .menu-list { margin-top: 20px; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .menu-list ul { list-style: none; padding: 0; }
        .menu-list li { margin: 10px 0; }
        .menu-list a { display: inline-block; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="dashboard_admin.php" class="active">Home</a>
            <a href="#about" onclick="alert('SIMPEG v1.0 - Aplikasi Sistem Informasi Kepegawaian Kampus/Instansi.')">About</a>
            <a href="#contact" onclick="alert('Hubungi IT Support: admin@simpeg.com / Telp: 0251-XXXXXX')">Contact</a>
        </div>
        <div>
            <span>Halo, <strong><?php echo $_SESSION['username']; ?> (Admin)</strong></span> | 
            <a href="index.php?page=logout">Log Out</a>
        </div>
    </div>

    <div class="container">
        <h2>Halaman Utama Admin</h2>
        <div class="grid">
            <div class="card">
                <h3>Total Pegawai Terdaftar</h3>
                <p style="font-size: 24px; font-weight: bold; color: #007bff;"><?php echo $total_pegawai; ?> Orang</p>
            </div>
            <div class="card">
                <h3>Status Sistem</h3>
                <p style="font-size: 24px; font-weight: bold; color: #28a745;">Aktif / Online</p>
            </div>
        </div>

        <div class="menu-list">
            <h3>Panel Kontrol Manajemen Admin:</h3>
            <ul>
                <li><a href="tampil_pegawai.php">👁️ Kelola & Data Master Pegawai (CRUD)</a></li>
                <li><a href="tambah_pegawai.php">➕ Tambah Pegawai Baru</a></li>
                <li><a href="rekap_absensi.php">📅 Rekapitulasi Absensi Seluruh Pegawai</a></li>
            </ul>
        </div>
    </div>

</body>
</html>