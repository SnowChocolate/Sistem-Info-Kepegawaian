<?php

class GajiModel
{
    private $db;

    public function __construct()
    {
        // Menyambungkan ke koneksi database global proyekmu
        global $conn; 
        $this->db = $conn;
    }

    // Ambil semua riwayat gaji pegawai
    public function getAll()
    {
        $query = "SELECT * FROM gaji";
        return $this->db->query($query);
    }

    // Ambil satu detail transaksi gaji berdasarkan ID
    public function getById($id)
    {
        $query = "SELECT * FROM gaji WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah data slip gaji baru
    public function create($idPegawai, $bulan, $gajiPokok, $tunjangan, $potongan, $totalGaji)
    {
        $query = "INSERT INTO gaji (id_pegawai, bulan, gaji_pokok, tunjangan, potongan, total_gaji) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isdddd", $idPegawai, $bulan, $gajiPokok, $tunjangan, $potongan, $totalGaji);
        return $stmt->execute();
    }

    // Hapus transaksi pencatatan gaji jika salah input
    public function delete($id)
    {
        $query = "DELETE FROM gaji WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}