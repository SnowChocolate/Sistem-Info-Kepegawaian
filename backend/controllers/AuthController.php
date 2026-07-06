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
        // 1. JIKA FORM DISUBMIT (METHOD POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Username dan Password wajib diisi.";
                header("Location: index.php?page=login");
                exit;
            }

            // Ambil data user berdasarkan username
            $user = $this->authModel->loginUser($username);
            if (!$user) {
                $_SESSION['error'] = "Username tidak ditemukan.";
                header("Location: index.php?page=login");
                exit;
            }

            // Verifikasi Password
            if (!password_verify($password, $user['password'])) {
                $_SESSION['error'] = "Password salah.";
                header("Location: index.php?page=login");
                exit;
            }

            // Set Session jika berhasil login
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Alihkan halaman sesuai Role
            if ($user['role'] == 'admin') {
                header("Location: index.php?page=dashboard_admin");
            } else {
                header("Location: index.php?page=dashboard_pegawai");
            }
            exit;
        }

        // 2. JIKA DIAKSES BIASA (METHOD GET)
        // Tampilkan halaman form login asli
        require_once __DIR__ . '/../../frontend/auth/view_login.php';
    }

    public function logout()
    {
        // session_start() sudah ada di index.php, tinggal bersihkan saja
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}