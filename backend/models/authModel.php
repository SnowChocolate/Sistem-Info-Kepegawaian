<?php

require_once __DIR__ . "/../config/koneksi.php";

class AuthModel
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function loginUser($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function cekUsername($username)
    {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function registerUser($username, $password, $role)
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