<?php
// 1. Hubungkan ke database
require_once __DIR__ . '/../config/koneksi.php';

// 2. Ambil ID pegawai dari parameter URL (misal: index.php?page=hapus_pegawai&id=5)
$id_pegawai = $_GET['id'] ?? '';

if (!empty($id_pegawai)) {
    // Inisialisasi class Database
    $database = new Database();
    $db = $database->conn;

    // 3. Query DELETE disesuaikan menggunakan kolom 'id' sebagai WHERE clause
    $query = "DELETE FROM pegawai WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $id_pegawai);

    // 4. Eksekusi query
    if ($stmt->execute()) {
        // Jika sukses, lempar kembali ke halaman daftar pegawai dengan tanda status
        header("Location: index.php?page=daftar_pegawai&status=sukses_hapus");
        exit;
    } else {
        echo "<div style='color: red; font-family: sans-serif; padding: 20px;'>
                <h3>⚠️ Gagal Menghapus Data</h3>
                <p>Error: " . $db->error . "</p>
                <a href='index.php?page=daftar_pegawai'>Kembali</a>
              </div>";
    }
} else {
    echo "ID Pegawai tidak valid atau tidak ditemukan.";
}
?>