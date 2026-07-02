<?php
// LOKASI FILE: models/authModel.php

class AuthModel {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    // ========================================================
    // FUNGSI UNTUK LOGIN (Kodingan asli milikmu)
    // ========================================================
    public function loginUser($username) {
        $username = mysqli_real_escape_string($this->db, $username);
        $query  = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($this->db, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    // ========================================================
    // FUNGSI TAMBAHAN UNTUK REGISTRASI
    // ========================================================
    
    // 1. Cek apakah username sudah kembar di database
    public function cekUsernameBaru($username) {
        $usernameClean = mysqli_real_escape_string($this->db, $username);
        $query = "SELECT username FROM users WHERE username = '$usernameClean'";
        $hasil = mysqli_query($this->db, $query);
        
        return mysqli_num_rows($hasil) > 0; // Menghasilkan true jika username sudah ada
    }

    // 2. Simpan user/pegawai baru ke database
    public function simpanUserBaru($username, $passwordTerenkripsi, $role) {
        $usernameClean = mysqli_real_escape_string($this->db, $username);
        $query = "INSERT INTO users (username, password, role) VALUES ('$usernameClean', '$passwordTerenkripsi', '$role')";
        
        return mysqli_query($this->db, $query); // Menghasilkan true jika berhasil disimpan
    }
}
?>