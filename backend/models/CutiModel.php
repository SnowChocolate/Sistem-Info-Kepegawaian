<?php

require_once __DIR__ . '/../config/Database.php';

class CutiModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM cuti ORDER BY created_at DESC";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM cuti WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function create($id_pegawai, $tanggal_mulai, $tanggal_selesai, $alasan, $status)
    {
        $sql = "INSERT INTO cuti
                (id_pegawai, tanggal_mulai, tanggal_selesai, alasan, status) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "issss",
            $id_pegawai,
            $tanggal_mulai,
            $tanggal_selesai,
            $alasan,
            $status
        );

        return $stmt->execute();
    }

    public function update($id, $tanggal_mulai, $tanggal_selesai, $alasan, $status)
    {
        $sql = "UPDATE cuti
                SET tanggal_mulai = ?,
                    tanggal_selesai = ?,
                    alasan = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssi",
            $tanggal_mulai,
            $tanggal_selesai,
            $alasan,
            $status,
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM cuti WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function getByPegawai($id_pegawai)
    {
        $sql = "SELECT * FROM cuti
                WHERE id_pegawai = ?
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id_pegawai);

        $stmt->execute();

        return $stmt->get_result();
    }
}