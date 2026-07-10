<?php

class PegawaiModel
{
    private $db;

    public function __construct()
    {
        // Menyambungkan ke koneksi database global proyekmu
        global $conn; 
        $this->db = $conn;
    }

    // Ambil semua data pegawai
    public function getAll()
    {
        $query = "SELECT * FROM pegawai";
        return $this->db->query($query);
    }

    public function delete($id)
{
    // Hapus data pegawai
    $stmt = $this->db->prepare("DELETE FROM pegawai WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Hapus akun user
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

    // Ambil data pegawai berdasarkan NIP (untuk validasi data unik)
    public function getByNip($nip)
    {
        $query = "SELECT * FROM pegawai WHERE nip = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $nip);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah data pegawai baru
    public function create($idUser, $nip, $nama, $jabatan, $telepon)
    {
        $query = "INSERT INTO pegawai (id_user, nip, nama, jabatan, telepon) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issss", $idUser, $nip, $nama, $jabatan, $telepon);
        return $stmt->execute();
    }

    // Update data pegawai
    public function update($id, $nama, $jabatan, $telepon)
    {
        $query = "UPDATE pegawai SET nama = ?, jabatan = ?, telepon = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $nama, $jabatan, $telepon, $id);
        return $stmt->execute();
    }
}