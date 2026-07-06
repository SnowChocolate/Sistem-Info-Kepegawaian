<?php

class AuthMiddleware
{
    public static function checkLogin()
    {
        //session_start();

        if (!isset($_SESSION['login'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    public static function checkAdmin()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=dashboard_pegawai");
            exit;
        }
    }
    
}

