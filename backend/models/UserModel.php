<?php

require_once __DIR__ . "/../config/koneksi.php";

class UserModel
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getAll()
    {
        $sql = "SELECT id, username, role, created_at, updated_at FROM users ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT id, username, role, created_at, updated_at FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($username, $password, $role)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sss",
            $username,
            $hashPassword,
            $role
        );

        return $stmt->execute();
    }

    public function update($id, $username, $role)
    {
        $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssi",
            $username,
            $role,
            $id
        );
        
        return $stmt->execute();
    }

    public function updatePassword($id, $password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "si",
            $hashPassword,
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}