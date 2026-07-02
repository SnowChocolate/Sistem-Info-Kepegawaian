<?php
session_start();
require_once 'koneksi.php'; // koneksi tetap di folder utama
/** @var mysqli $koneksi */

require_once 'AuthModel.php';
require_once 'AuthController.php';

$authModel = new AuthModel($koneksi);
$authController = new AuthController($authModel);

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        $authController->indexLogin();
        break;
    case 'dashboard_admin':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') header("Location: index.php?page=login");
        require_once 'dashboard_admin.php';
        break;
    case 'dashboard_pegawai':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') header("Location: index.php?page=login");
        require_once 'dashboard.php';
        break;
    case 'logout': // <--- Tambahkan case logout ini!
        $authController->logout();
        break;
}