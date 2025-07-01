<?php

namespace App\Controllers;

use App\Models\WisataModel;
use CodeIgniter\RESTful\ResourceController;

class WisataEditController extends ResourceController
{
    protected $modelName = 'App\Models\WisataModel';
    protected $format    = 'json';

    // GET data wisata by ID
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound("Data dengan ID $id tidak ditemukan.");
    }

    // PUT /wisataedit/{id}
    public function update($id = null)
    {
        $model = new WisataModel();

        // Validasi data wisata
        if (!$model->find($id)) {
            return $this->failNotFound("Data dengan ID $id tidak ditemukan.");
        }

        $data = [
            'namawisata'    => $this->request->getPost('namawisata'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'alamat'        => $this->request->getPost('alamat'),
            'namakategori'  => $this->request->getPost('namakategori'),
        ];

        // Jika ada foto baru
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $id . '.' . $foto->getExtension();
            $uploadPath = ROOTPATH . 'public/uploads';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $foto->move($uploadPath, $namaFoto);
            $data['foto'] = $namaFoto;
        }

        // Simpan data ke database
        if ($model->update($id, $data)) {
            return $this->respond(['message' => 'Data wisata berhasil diperbarui.']);
        }

        return $this->fail('Gagal mengupdate data.');
    }

    // DELETE /wisataedit/{id}
        public function delete($id = null)
        {
            

            if (!$this->model->find($id)) { 
                return $this->failNotFound("Data wisata dengan ID $id tidak ditemukan.");
            }

            if ($this->model->delete($id)) { 
                return $this->respondDeleted(['message' => 'Data wisata berhasil dihapus.']);
            }

            return $this->fail('Gagal menghapus data wisata.');
        }
}
