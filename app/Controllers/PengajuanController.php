<?php
namespace App\Controllers;

use App\Models\PengajuanModel;
use App\Models\PenggunaModel;
use CodeIgniter\RESTful\ResourceController;

class PengajuanController extends ResourceController
{
    protected $modelName = 'App\Models\PengajuanModel';
    protected $format = 'json';

    public function create()
    {
        $data = $this->request->getPost();

        // Validasi input data
        if (empty($data['idpengguna']) || empty($data['namawisata']) || empty($data['deskripsi']) || empty($data['alamat']) || empty($data['foto']) || empty($data['idkategori'])) {
            return $this->failValidationErrors('Semua kolom harus diisi.');
        }

        // Validasi apakah idpengguna ada di tabel pengguna
        $penggunaModel = new PenggunaModel();
        $pengguna = $penggunaModel->find($data['idpengguna']);

        if (!$pengguna) {
            return $this->fail('idpengguna tidak valid atau tidak ditemukan.');
        }

        // Menyimpan data ke tabel pengajuan
        $pengajuanData = [
            'idpengguna' => $data['idpengguna'],
            'namawisata' => $data['namawisata'],
            'deskripsi' => $data['deskripsi'],
            'alamat' => $data['alamat'],
            'foto' => $data['foto'],
            'idkategori' => $data['idkategori'],
        ];

        if ($this->model->insert($pengajuanData)) {
            return $this->respondCreated(['message' => 'Pengajuan berhasil ditambahkan']);
        } else {
            return $this->fail('Pengajuan gagal disimpan');
        }
    }
}
