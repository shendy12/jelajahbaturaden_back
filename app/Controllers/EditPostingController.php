<?php

namespace App\Controllers;

use App\Models\WisataModel;
use CodeIgniter\RESTful\ResourceController;

class WisataEditController extends ResourceController
{
    protected $modelName = 'App\Models\WisataModel';
    protected $format    = 'json';

    // Get data wisata by ID
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound("Data dengan ID $id tidak ditemukan.");
    }

    // Update wisata berdasarkan ID
    public function update($id = null)
    {
        $input = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound("Data dengan ID $id tidak ditemukan.");
        }

        if ($this->model->update($id, $input)) {
            return $this->respond(['message' => 'Data wisata berhasil diperbarui.']);
        }

        return $this->fail('Gagal mengupdate data.');
    }

    // Hapus data wisata
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound("Data dengan ID $id tidak ditemukan.");
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['message' => 'Data wisata berhasil dihapus.']);
        }

        return $this->fail('Gagal menghapus data.');
    }
}
