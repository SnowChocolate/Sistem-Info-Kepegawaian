<?php

require_once __DIR__ . '/../models/GajiModel.php';

class GajiController
{
    private $model;

    public function __construct()
    {
        $this->model = new GajiModel();
    }

    // Mengambil seluruh riwayat gaji pegawai
    public function index()
    {
        $result = $this->model->getAll();
        $gaji = [];

        while ($row = $result->fetch_assoc()) {
            $gaji[] = $row;
        }

        echo json_encode([
            "status" => true,
            "message" => "Data gaji berhasil diambil",
            "data" => $gaji
        ]);
    }

    // Melihat detail slip gaji berdasarkan ID Transaksi Gaji
    public function show($id)
    {
        $gaji = $this->model->getById($id);

        if (!$gaji) {
            http_response_code(404);
            echo json_encode([
                "status" => false,
                "message" => "Data transaksi gaji tidak ditemukan"
            ]);
            return;
        }

        echo json_encode([
            "status" => true,
            "data" => $gaji
        ]);
    }

    // Menginput data slip gaji baru
    public function store()
    {
        $idPegawai = $_POST['id_pegawai'] ?? '';
        $bulan     = $_POST['bulan'] ?? ''; // Contoh input: '2026-07'
        $gajiPokok = $_POST['gaji_pokok'] ?? 0;
        $tunjangan = $_POST['tunjangan'] ?? 0;
        $potongan  = $_POST['potongan'] ?? 0;

        if (empty($idPegawai) || empty($bulan)) {
            http_response_code(400);
            echo json_encode([
                "status" => false,
                "message" => "ID Pegawai dan Bulan wajib diisi."
            ]);
            return;
        }

        // Kalkulasi total bersih otomatis di backend
        $totalGaji = ($gajiPokok + $tunjangan) - $potongan;

        $this->model->create($idPegawai, $bulan, $gajiPokok, $tunjangan, $potongan, $totalGaji);

        echo json_encode([
            "status" => true,
            "message" => "Data gaji berhasil ditambahkan"
        ]);
    }

    // Menghapus data transaksi gaji (jika admin salah input)
    public function destroy($id)
    {
        $this->model->delete($id);
        echo json_encode([
            "status" => true,
            "message" => "Data transaksi gaji berhasil dihapus"
        ]);
    }
}