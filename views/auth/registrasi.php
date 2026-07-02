<?php
session_start();
// Menghubungkan ke koneksi
require_once 'koneksi.php'; 

/** @var mysqli $koneksi */ // <-- Tambahkan baris komentar ini untuk menghilangkan merah

$pesan = "";

if (isset($_POST['register'])) {
    // Baris di bawah ini dijamin tidak akan merah lagi
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'pegawai'; 

    if ($password !== $confirm_password) {
        $pesan = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username sudah ada
        $cek_username  = "SELECT username FROM users WHERE username = '$username'";
        $hasil_cek     = mysqli_query($koneksi, $cek_username);

        if (mysqli_num_rows($hasil_cek) > 0) {
            $pesan = "Username sudah digunakan oleh akun lain!";
        } else {
            // Enkripsi password
            $password_terenkripsi = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_terenkripsi', '$role')";
            
            if (mysqli_query($koneksi, $query)) {
                // Jika sukses, alihkan ke login.php
                header("Location: login.php?pesan=registrasi_sukses");
                exit();
            } else {
                $pesan = "Terjadi kesalahan sistem, gagal mendaftar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - SIMPEG</title>
</head>
<body>
    <h2>Form Registrasi Pegawai</h2>

    <?php if(!empty($pesan)): ?>
        <p style="color: red;"><?php echo $pesan; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Username</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit" name="register">Daftar Sekarang</button>
    </form>
    <br>
    <a href="login.php">Sudah punya akun? Login di sini</a>
</body>
</html>