<?php

require_once __DIR__ . '/../models/AuthModel.php';

class AuthController
{
    private AuthModel $authModel;

    public function __construct(AuthModel $authModel)
    {
        $this->authModel = $authModel; 
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Username dan Password wajib diisi.";
                header("Location: index.php?page=login");
                exit;
            }

            $user = $this->authModel->loginUser($username);
            if (!$user) {
                $_SESSION['error'] = "Username tidak ditemukan.";
                header("Location: index.php?page=login");
                exit;
            }

            // === JALUR VERIFIKASI MULTI-ROLE (LANGSUNG TO THE POINT) ===
            if ($user['role'] === 'admin') {
                if ($password !== $user['password'] && md5($password) !== $user['password'] && !password_verify($password, $user['password'])) {
                    $_SESSION['error'] = "Password salah.";
                    header("Location: index.php?page=login");
                    exit;
                }
            } else {
                if (!password_verify($password, $user['password'])) {
                    $_SESSION['error'] = "Password salah.";
                    header("Location: index.php?page=login");
                    exit;
                }
            }

            // Set Session jika lolos
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Alihkan halaman
            if ($user['role'] == 'admin') {
                header("Location: index.php?page=dashboard_admin");
            } else {
                header("Location: index.php?page=dashboard_pegawai");
            }
            exit;
        }

        require_once __DIR__ . '/../../frontend/auth/view_login.php';
    }

    public function register() {
    // Ambil koneksi database yang sudah diinisialisasi oleh sistem MVC kamu
    global $conn; 
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $password = trim($_POST['password']);
        $jabatan  = mysqli_real_escape_string($conn, trim($_POST['jabatan'])); // AMBIL INPUT JABATAN DARI FORM

        if (empty($username) || empty($password) || empty($jabatan)) {
            echo "<script>alert('Semua data wajib diisi!'); window.history.back();</script>";
            exit;
        }

        // 1. Cek apakah username sudah ada di tabel users
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
        if (mysqli_num_rows($cek_user) > 0) {
            echo "<script>alert('Username sudah terdaftar!'); window.history.back();</script>";
            exit;
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // 2. Simpan Akun ke tabel 'users'
        $query_users = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', 'pegawai')";
        $simpan_users = mysqli_query($conn, $query_users);

        if ($simpan_users) {
            $id_user_baru = mysqli_insert_id($conn);

            // 3. SINKRONISASI: Simpan detail data ke tabel 'pegawai' sesuai jabatan yang diisi!
            $query_pegawai = "INSERT INTO pegawai (id_user, nama, jabatan, sisa_cuti) 
                              VALUES ('$id_user_baru', '$username', '$jabatan', 12)";
            $simpan_pegawai = mysqli_query($conn, $query_pegawai);

            if ($simpan_pegawai) {
                echo "<script>alert('Registrasi Berhasil! Data Pegawai SINKRON.'); window.location.href='index.php?page=login';</script>";
                exit;
            } else {
                die("Akun users dibuat, tapi tabel pegawai menolak: " . mysqli_error($conn));
            }
        } else {
            echo "<script>alert('Gagal registrasi akun.'); window.history.back();</script>";
            exit;
        }
    }

    // Jika load halaman biasa, panggil tampilan form register kamu
    // (Sesuaikan dengan jalur include view register asli bawaan proyekmu jika berbeda)
    require_once __DIR__ . '/../../frontend/auth/registrasi.php';
}

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}