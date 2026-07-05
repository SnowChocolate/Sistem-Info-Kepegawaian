<?php

require_once __DIR__ . '/../models/DivisiModel.php';

class DivisiController
{
    private $model;

    public function __construct()
    {
        $this->model = new DivisiModel();
    }

    public function index()
    {
        $result = $this->model->getAll();
        $divisi = [];

        while ($row = $result->fetch_assoc()) {
            $divisi[] = $row;
        }

        echo json_encode([
            "status"  => true,
            "message" => "Data divisi berhasil diambil",
            "data"    => $divisi
        ]);
    }

    public function show($id)
    {
        $divisi = $this->model->getById($id);

        if (!$divisi) {
            http_response_code(404);

            echo json_encode([
                "status"  => false,
                "message" => "Data divisi tidak ditemukan"
            ]);

            return;
        }

        echo json_encode([
            "status" => true,
            "data"   => $divisi
        ]);
    }

    public function store()
    {
        $namaDivisi = trim($_POST['nama_divisi'] ?? '');

        if (empty($namaDivisi)) {
            http_response_code(400);

            echo json_encode([
                "status"  => false,
                "message" => "Nama divisi wajib diisi."
            ]);

            return;
        }

        $this->model->create($namaDivisi);

        echo json_encode([
            "status"  => true,
            "message" => "Data divisi berhasil ditambahkan"
        ]);
    }

    public function update()
    {
        parse_str(file_get_contents("php://input"), $put);
        $id          = $put['id'] ?? '';
        $namaDivisi  = trim($put['nama_divisi'] ?? '');

        if (empty($id) || empty($namaDivisi)) {
            http_response_code(400);

            echo json_encode([
                "status"  => false,
                "message" => "ID dan nama divisi wajib diisi."
            ]);

            return;
        }

        $this->model->update($id, $namaDivisi);

        echo json_encode([
            "status"  => true,
            "message" => "Data divisi berhasil diperbarui"
        ]);
    }

    public function destroy($id)
    {
        $this->model->delete($id);

        echo json_encode([
            "status"  => true,
            "message" => "Data divisi berhasil dihapus"
        ]);
    }
}