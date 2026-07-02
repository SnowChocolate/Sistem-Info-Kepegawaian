<?php
// LOKASI FILE: controllers/authController.php

class AuthController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    // ========================================================
    // 1. FUNGSI LOGIN (Kodingan asli milikmu)
    // ========================================================
    public function indexLogin() {
        $pesan_error = "";

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user_data = $this->model->loginUser($username);

            if ($user_data) {
                // 1. JALUR KHUSUS ADMIN
                if ($username === 'admin' && $password === 'admin123') {
                    $_SESSION['id_user']  = $user_data['id'];
                    $_SESSION['username'] = $user_data['username'];
                    $_SESSION['role']     = 'admin';

                    header("Location: index.php?page=dashboard_admin");
                    exit();
                } 
                // 2. JALUR PEGAWAI BIASA
                else if (password_verify($password, $user_data['password'])) {
                    $_SESSION['id_user']  = $user_data['id'];
                    $_SESSION['username'] = $user_data['username'];
                    $_SESSION['role']     = $user_data['role'];

                    if ($_SESSION['role'] === 'admin') {
                        header("Location: index.php?page=dashboard_admin");
                    } else {
                        header("Location: index.php?page=dashboard_pegawai");
                    }
                    exit(); 
                } else {
                    $pesan_error = "Password yang Anda masukkan salah!";
                }
            } else {
                $pesan_error = "Username tidak terdaftar!";
            }
        }

        // Panggil file tampilan login
        require_once 'view_login.php'; 
    }

    // ========================================================
    // 2. FUNGSI BARU: PROSES REGISTRASI (Tambahan)
    // ========================================================
    public function prosesRegistrasi() {
        // Memastikan data dikirim lewat metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username         = $_POST['username'];
            $password         = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role             = 'pegawai'; // Default role pendaftar baru

            // Validasi 1: Pastikan password cocok
            if ($password !== $confirm_password) {
                header("Location: index.php?page=registrasi&pesan=password_tidak_cocok");
                exit();
            }

            // Validasi 2: Cek duplikasi username lewat Model
            if ($this->model->cekUsernameBaru($username)) {
                header("Location: index.php?page=registrasi&pesan=username_sudah_ada");
                exit();
            }

            // Enkripsi Password
            $password_terenkripsi = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data ke database lewat Model
            if ($this->model->simpanUserBaru($username, $password_terenkripsi, $role)) {
                header("Location: index.php?page=login&pesan=registrasi_sukses");
                exit();
            } else {
                header("Location: index.php?page=registrasi&pesan=registrasi_gagal");
                exit();
            }

        } else {
            header("Location: index.php?page=registrasi");
            exit();
        }
    }

    // ========================================================
    // 3. FUNGSI LOGOUT (Kodingan asli milikmu)
    // ========================================================
    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: index.php?page=login&pesan=logout_sukses");
        exit();
    }
}
?>