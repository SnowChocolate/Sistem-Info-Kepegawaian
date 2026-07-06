<?php

require_once __DIR__ . '/../models/PegawaiModel.php';

class PegawaiController
{
    private $model;

    public function __construct()
    {
        $this->model = new PegawaiModel();
    }

    // Mengambil semua data pegawai
    public function index()
    {
        $result = $this->model->getAll();
        $pegawai = [];

        while ($row = $result->fetch_assoc()) {
            $pegawai[] = $row;
        }

        echo json_encode([
            "status" => true,
            "message" => "Data pegawai berhasil diambil",
            "data" => $pegawai
        ]);
    }

    // Mengambil satu data pegawai berdasarkan ID
    public function show($id)
    {
        $pegawai = $this->model->getById($id);

        if (!$pegawai) {
            http_response_code(404);
            echo json_encode([
                "status" => false,
                "message" => "Data pegawai tidak ditemukan"
            ]);
            return;
        }

        echo json_encode([
            "status" => true,
            "data" => $pegawai
        ]);
    }

    // Menambah data pegawai baru
    public function store()
    {
        $idUser   = $_POST['id_user'] ?? ''; // Relasi ke tabel users
        $nip      = $_POST['nip'] ?? '';
        $nama     = $_POST['nama'] ?? '';
        $jabatan  = $_POST['jabatan'] ?? '';
        $telepon  = $_POST['telepon'] ?? '';

        if (empty($idUser) || empty($nip) || empty($nama) || empty($jabatan)) {
            http_response_code(400);
            echo json_encode([
                "status" => false,
                "message" => "Field utama (ID User, NIP, Nama, Jabatan) wajib diisi."
            ]);
            return;
        }

        // Validasi NIP unik
        if ($this->model->getByNip($nip)) {
            http_response_code(409);
            echo json_encode([
                "status" => false,
                "message" => "NIP sudah digunakan oleh pegawai lain."
            ]);
            return;
        }

        $this->model->create($idUser, $nip, $nama, $jabatan, $telepon);

        echo json_encode([
            "status" => true,
            "message" => "Data pegawai berhasil ditambahkan"
        ]);
    }

    // Memperbarui data pegawai
    public function update()
    {
        parse_str(file_get_contents("php://input"), $put);

        $id       = $put['id'] ?? '';
        $nama     = $put['nama'] ?? '';
        $jabatan  = $put['jabatan'] ?? '';
        $telepon  = $put['telepon'] ?? '';

        if (empty($id) || empty($nama) || empty($jabatan)) {
            http_response_code(400);
            echo json_encode([
                "status" => false,
                "message" => "ID, Nama, dan Jabatan wajib diisi"
            ]);
            return;
        }

        $this->model->update($id, $nama, $jabatan, $telepon);

        echo json_encode([
            "status" => true,
            "message" => "Data pegawai berhasil diperbarui"
        ]);
    }

    // Menghapus data pegawai
    public function destroy($id)
    {
        $this->model->delete($id);
        echo json_encode([
            "status" => true,
            "message" => "Data pegawai berhasil dihapus"
        ]);
    }
}