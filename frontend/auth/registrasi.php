<?php
// 1. KONEKSI LANGSUNG KE DATABASE
$conn_direct = mysqli_connect("localhost", "root", "", "sistem_info_kepegawaian");

if (!$conn_direct) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 2. PROSES DAFTAR KETIKA TOMBOL DIKLIK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_registrasi'])) {
    $username = mysqli_real_escape_string($conn_direct, trim($_POST['username']));
    $password = trim($_POST['password']);
    $jabatan  = mysqli_real_escape_string($conn_direct, trim($_POST['jabatan'])); // <-- INI HARUS TEPAT
    
    if (empty($username) || empty($password) || empty($jabatan)) {
        echo "<script>alert('Semua data wajib diisi!'); window.history.back();</script>";
        exit;
    }
    
    // Cek apakah username sudah dipakai
    $cek_user = mysqli_query($conn_direct, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terdaftar!'); window.history.back();</script>";
        exit;
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan ke tabel users
        $query_users = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', 'pegawai')";
        $simpan_users = mysqli_query($conn_direct, $query_users);
        
        if ($simpan_users) {
            $id_user_baru = mysqli_insert_id($conn_direct);

            // SIMPAN KE TABEL PEGAWAI (Menggunakan variabel $jabatan yang diinput user)
            $query_pegawai = "INSERT INTO pegawai (id_user, nama, jabatan, sisa_cuti) 
                              VALUES ('$id_user_baru', '$username', '$jabatan', 12)";
            
            $simpan_pegawai = mysqli_query($conn_direct, $query_pegawai);
            
            if ($simpan_pegawai) {
                echo "<script>alert('Registrasi Berhasil!'); window.location.href='index.php?page=daftar_pegawai';</script>";
                exit;
            } else {
                die("Gagal simpan ke tabel pegawai: " . mysqli_error($conn_direct));
            }
        } else {
            echo "<script>alert('Gagal membuat akun.'); window.history.back();</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pegawai Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Daftar Akun Baru</h2>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required placeholder="Username baru"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan / Posisi</label>
                <input type="text" name="jabatan" required placeholder="Contoh: Manager, HRD, IT Support"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-2">
                <button type="submit" name="submit_registrasi" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-md transition-all">
                    Buat Akun Pegawai
                </button>
            </div>
        </form>
    </div>
</body>
</html>