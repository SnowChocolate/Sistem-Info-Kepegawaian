<?php

require_once __DIR__ . "/../config/koneksi.php";

class AbsensiModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM absensi ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM absensi WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByPegawai($idPegawai)
    {
        $sql = "SELECT * FROM absensi WHERE id_pegawai = ? ORDER BY tanggal DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idPegawai);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create($idPegawai, $tanggal, $jamMasuk, $jamKeluar, $status)
    {
        $sql = "INSERT INTO absensi (id_pegawai, tanggal, jam_masuk, jam_keluar, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "issss",
            $idPegawai,
            $tanggal,
            $jamMasuk,
            $jamKeluar,
            $status
        );

        return $stmt->execute();
    }

    public function update($id, $tanggal, $jamMasuk, $jamKeluar, $status)
    {
        $sql = "UPDATE absensi
            SET tanggal = ?,
                jam_masuk = ?,
                jam_keluar = ?,
                status = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssi",
            $tanggal,
            $jamMasuk,
            $jamKeluar,
            $status,
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM absensi WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}