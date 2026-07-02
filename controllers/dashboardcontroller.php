<?php
class DashboardController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function indexPegawai() {
        // Proteksi Session pindah ke sini
        if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pegawai') {
            header("Location: index.php?page=login");
            exit();
        }

        $username_aktif = $_SESSION['username'];
        // Panggil fungsi dari Model
        $data_pegawai = $this->model->getProfilPegawai($username_aktif);

        // Lempar variabel $data_pegawai ke file Tampilan (View)
        require_once 'views/dashboard.php';
    }
}