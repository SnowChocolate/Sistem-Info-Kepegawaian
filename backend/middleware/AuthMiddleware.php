<?php

class AuthMiddleware
{
    // Pengaman: Semua user (Admin & Pegawai) harus login dulu
    public static function checkLogin()
    {
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    // Khusus Admin: Menjaga halaman Admin agar tidak diintip oleh Pegawai
    public static function checkAdmin()
    {
        self::checkLogin(); // Pastikan sudah login

        if ($_SESSION['role'] !== 'admin') {
            // Jika yang masuk adalah PEGAWAI, arahkan paksa ke dashboard pegawai sendiri
            header("Location: index.php?page=dashboard_pegawai");
            exit;
        }
    }
}