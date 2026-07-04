<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: login.php");
    exit();
}
require_once 'koneksi.php';
/** @var mysqli $koneksi */

$username_aktif = $_SESSION['username'];
$username_aktif = $_SESSION['username'];

// Query JOIN: Mencari data pegawai berdasarkan username yang ada di tabel users
$query_profil = "SELECT pegawai.* FROM pegawai 
                 JOIN users ON pegawai.id_user = users.id 
                 WHERE users.username = '$username_aktif'";

$result_profil = mysqli_query($koneksi, $query_profil);
$data_pegawai = ($result_profil && mysqli_num_rows($result_profil) > 0) ? mysqli_fetch_assoc($result_profil) : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pegawai - SIMPEG</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        /* Style Navbar */
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #28a745; padding: 10px 20px; color: #fff; }
        .nav-links { display: flex; gap: 15px; }
        .nav-links a { color: #fff; text-decoration: none; padding: 8px 12px; border-radius: 4px; }
        .nav-links a:hover, .nav-links a.active { background: #218838; }
        .btn-logout { color: #fff; text-decoration: underline; font-weight: bold; }
        
        .container { padding: 20px; display: flex; gap: 20px; }
        .profile-card { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 35%; border-top: 4px solid #28a745; }
        .menu-card { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 65%; border-top: 4px solid #17a2b8; }
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
        .btn-menu { display: block; padding: 15px; background: #17a2b8; color: white; text-decoration: none; text-align: center; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table td { padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="dashboard.php" class="active">Home</a>
            <a href="#about" onclick="alert('SIMPEG - Akses Portal Mandiri Pegawai.')">About</a>
            <a href="#contact" onclick="alert('Hubungi Kepegawaian/HRD di Gedung Rektorat Lantai 2.')">Contact</a>
        </div>
        <div>
            <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span> | 
            <a href="index.php?page=logout">Log Out</a>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <h3>Profil Saya</h3>
            <?php if ($data_pegawai): ?>
                <table>
                    <tr><td>Nama</td><td>: <?php echo $data_pegawai['nama'] ?? '-'; ?></td></tr>
                    <tr><td>Jabatan</td><td>: <?php echo $data_pegawai['jabatan'] ?? '-'; ?></td></tr>
                </table>
            <?php else: ?>
                <p style="color: red; font-style: italic;">Data profil belum lengkap.</p>
            <?php endif; ?>
        </div>

        <div class="menu-card">
            <h3>Fitur Mandiri</h3>
            <div class="menu-grid">
                <a href="absensi.php" class="btn-menu">🕒 Absen Masuk / Pulang</a>
                <a href="riwayat_absen.php" class="btn-menu">📅 Riwayat Kehadiran</a>
            </div>
        </div>
    </div>

</body>
</html>