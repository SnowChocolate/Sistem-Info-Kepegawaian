<?php

require_once __DIR__ . '/../models/CutiModel.php';

class CutiController
{
    private $model;

    public function __construct()
    {
        $this->model = new CutiModel();
    }

    public function index()
    {
        $result = $this->model->getAll();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
    }

    public function store()
    {
        // nanti tambah cuti
    }

    public function update()
    {
        // nanti update cuti
    }

    public function destroy()
    {
        // nanti hapus cuti
    }
}