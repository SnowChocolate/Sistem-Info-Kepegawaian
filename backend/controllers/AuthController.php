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
                // Admin: Cek teks polos biasa atau MD5 atau hash
                if ($password !== $user['password'] && md5($password) !== $user['password'] && !password_verify($password, $user['password'])) {
                    $_SESSION['error'] = "Password salah.";
                    header("Location: index.php?page=login");
                    exit;
                }
            } else {
                // Pegawai: Wajib lewat password_verify hasil registrasi
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

    

  // ==========================================
    // 🛠️ FUNGSI REGISTRASI PEGAWAI
    // ==========================================
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $role = 'pegawai'; 

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Username dan Password wajib diisi.";
                header("Location: index.php?page=register");
                exit;
            }

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $simpan = $this->authModel->registerUser($username, $password_hashed, $role);

            if ($simpan) {
                $_SESSION['success'] = "Registrasi sukses! Silakan login dengan akun Anda.";
                header("Location: index.php?page=login");
            } else {
                $_SESSION['error'] = "Registrasi gagal! Username mungkin sudah digunakan.";
                header("Location: index.php?page=register");
            }
            exit;
        }

        // 🛠️ PASTIKAN DI SINI MEMANGGIL view_registrasi.php Sesuai Nama Filemu
        require_once 'frontend/auth/registrasi.php';
    }
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}