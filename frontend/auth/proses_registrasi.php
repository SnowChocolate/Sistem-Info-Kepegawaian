<?php
// Pastikan koneksi langsung ke nama database yang benar
$conn_direct = mysqli_connect("localhost", "root", "", "sistem_info_kepegawaian");

if (!$conn_direct) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn_direct, trim($_POST['username']));
    $password = trim($_POST['password']);
    $jabatan  = mysqli_real_escape_string($conn_direct, trim($_POST['jabatan'])); 
    
    if (empty($username) || empty($password) || empty($jabatan)) {
        echo "<script>alert('Semua data wajib diisi!'); window.history.back();</script>";
        exit;
    }
    
    // 1. Cek duplikasi username
    $cek_user = mysqli_query($conn_direct, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terdaftar!'); window.history.back();</script>";
        exit;
    } 
    
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
    // 2. Simpan ke tabel 'users'
    $query_users = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', 'pegawai')";
    $simpan_users = mysqli_query($conn_direct, $query_users);
    
    if ($simpan_users) {
        $id_user_baru = mysqli_insert_id($conn_direct);

        // 3. Simpan ke tabel 'pegawai'
        $query_pegawai = "INSERT INTO pegawai (id_user, nama, jabatan, sisa_cuti) 
                          VALUES ('$id_user_baru', '$username', '$jabatan', 12)";
        
        $simpan_pegawai = mysqli_query($conn_direct, $query_pegawai);
        
        if ($simpan_pegawai) {
            echo "<script>alert('Registrasi Berhasil! Data Pegawai SINKRON.'); window.location.href='index.php?page=login';</script>";
            exit;
        } else {
            // Jika tabel pegawai gagal, muntahkan errornya ke layar agar kelihatan
            die("User berhasil dibuat, tapi TABEL PEGAWAI MENOLAK. Error: " . mysqli_error($conn_direct));
        }
    } else {
        echo "<script>alert('Gagal membuat akun user.'); window.history.back();</script>";
        exit;
    }
}
?>