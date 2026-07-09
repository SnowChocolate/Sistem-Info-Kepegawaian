<?php

class Database
{
    $host = "sql107.infinityfree.com";
    $user = "if0_42371485";
    $pass = "0PA2Hu4xFZVLV";
    $db   = "if0_42371485_XXX";

    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($this->conn->connect_error) {
            die("Koneksi database gagal: " . $this->conn->connect_error);
        }
    }
}