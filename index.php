<?php

session_start();

require_once 'koneksi.php';
require_once 'AuthModel.php';
require_once 'AuthController.php';
require_once 'AuthMiddleware.php';

$authModel = new AuthModel($koneksi);
$authController = new AuthController($authModel);

$page = $_GET['page'] ?? 'login';

switch ($page) {

    case 'login':
        $authController->indexLogin();
        break;

    case 'dashboard_admin':
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin();
        require_once 'views/dashboard/admin.php';
        break;

    case 'dashboard_pegawai':
        AuthMiddleware::checkLogin();

        require_once 'views/dashboard/pegawai.php';
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        echo "404 - Page Not Found";
        break;
}