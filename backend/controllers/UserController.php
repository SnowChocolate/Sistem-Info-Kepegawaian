<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $model;
    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index()
    {
        $result = $this->model->getAll();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode([
            "status" => true,
            "message" => "Data user berhasil diambil",
            "data" => $users
        ]);
    }

    public function show($id)
    {
        $user = $this->model->getById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
            return;
        }

        echo json_encode([
            "status" => true,
            "data" => $user
        ]);
    }

    public function store()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? 'pegawai';

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                "status" => false,
                "message" => "Username dan Password wajib diisi"
            ]);

            return;
        }

        $cek = $this->model->getByUsername($username);
        if ($cek) {
            http_response_code(409);
            echo json_encode([
                "status" => false,
                "message" => "Username sudah digunakan"
            ]);

            return;
        }

        $this->model->create($username, $password, $role);
        echo json_encode([
            "status" => true,
            "message" => "User berhasil ditambahkan"
        ]);
    }

    public function update()
    {
        parse_str(file_get_contents("php://input"), $put);
        $id       = $put['id'];
        $username = $put['username'];
        $role     = $put['role'];

        $this->model->update($id, $username, $role);
        echo json_encode([
            "status" => true,
            "message" => "User berhasil diperbarui"
        ]);
    }

    public function updatePassword()
    {
        parse_str(file_get_contents("php://input"), $put);
        $id = $put['id'];
        $password = $put['password'];
        $this->model->updatePassword($id, $password);
        echo json_encode([
            "status" => true,
            "message" => "Password berhasil diperbarui"
        ]);
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        echo json_encode([
            "status" => true,
            "message" => "User berhasil dihapus"
        ]);
    }
}