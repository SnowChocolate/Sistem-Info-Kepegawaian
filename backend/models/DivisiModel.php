<?php

require_once __DIR__ . "/../config/koneksi.php";

class DivisiModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM divisi ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM divisi WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function create($namaDivisi)
    {
        $sql = "INSERT INTO divisi (nama_divisi)
                VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $namaDivisi);

        return $stmt->execute();
    }

    public function update($id, $namaDivisi)
    {
        $sql = "UPDATE divisi SET nama_divisi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $namaDivisi, $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM divisi WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}