<?php

require_once __DIR__ . "/../config/koneksi.php";

class AuthModel
{
   private mysqli $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function loginUser(string $username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function cekUsername(string $username)
    {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function registerUser(string $username,string  $password, string $role)
    {
        $sql = "INSERT INTO users(username,password,role) VALUES(?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sss",
            $username,
            $password,
            $role
        );

        return $stmt->execute();
    }
}