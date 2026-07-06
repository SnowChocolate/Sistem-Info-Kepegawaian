<?php

require_once __DIR__ . '/../models/AbsensiModel.php';

class AbsensiController
{
    private $model;
    public function __construct()
    {
        $this->model = new AbsensiModel();
    }

    public function index()
    {
        $result = $this->model->getAll();
        $absensi = [];

        while ($row = $result->fetch_assoc()) {
            $absensi[] = $row;
        }

        echo json_encode([
            "status" => true,
            "message" => "Data absensi berhasil diambil",
            "data" => $absensi
        ]);
    }

    public function show($id)
    {
        $absensi = $this->model->getById($id);

        if (!$absensi) {
            http_response_code(404);

            echo json_encode([
                "status" => false,
                "message" => "Data absensi tidak ditemukan"
            ]);

            return;
        }

        echo json_encode([
            "status" => true,
            "data" => $absensi
        ]);
    }

    public function store()
    {
        $idPegawai = $_POST['id_pegawai'] ?? '';
        $tanggal   = $_POST['tanggal'] ?? '';
        $jamMasuk  = $_POST['jam_masuk'] ?? '';
        $jamKeluar = $_POST['jam_keluar'] ?? '';
        $status    = $_POST['status'] ?? '';

        if (
            empty($idPegawai) ||
            empty($tanggal) ||
            empty($jamMasuk) ||
            empty($status)
        ) {
            http_response_code(400);

            echo json_encode([
                "status" => false,
                "message" => "Semua field wajib diisi."
            ]);

            return;
        }

        $this->model->create(
            $idPegawai,
            $tanggal,
            $jamMasuk,
            $jamKeluar,
            $status
        );

        echo json_encode([
            "status" => true,
            "message" => "Data absensi berhasil ditambahkan"
        ]);
    }

    public function update()
    {
        parse_str(file_get_contents("php://input"), $put);

        $id         = $put['id'] ?? '';
        $tanggal    = $put['tanggal'] ?? '';
        $jamMasuk   = $put['jam_masuk'] ?? '';
        $jamKeluar  = $put['jam_keluar'] ?? '';
        $status     = $put['status'] ?? '';

        if (empty($id)) {
            http_response_code(400);

            echo json_encode([
                "status" => false,
                "message" => "ID wajib diisi"
            ]);

            return;
        }

        $this->model->update(
            $id,
            $tanggal,
            $jamMasuk,
            $jamKeluar,
            $status
        );

        echo json_encode([
            "status" => true,
            "message" => "Data absensi berhasil diperbarui"
        ]);
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        echo json_encode([
            "status" => true,
            "message" => "Data absensi berhasil dihapus"
        ]);
    }
}