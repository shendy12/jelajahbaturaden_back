<?php namespace App\Controllers;

use App\Models\PengajuanModel;
use CodeIgniter\RESTful\ResourceController;

class PengajuanController extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';

    public function create()
    {
        helper(['form']);
        $validation = \Config\Services::validation();

        $validationRules = [
            'idpengguna' => 'required|integer',
            'namawisata' => 'required',
            'deskripsi'  => 'required',
            'alamat'     => 'required',
            'idkategori' => 'required|integer',
            'foto'       => 'uploaded[foto]|is_image[foto]|max_size[foto,2048]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        // Ambil file foto
        $file = $this->request->getFile('foto');
        $fotoData = file_get_contents($file->getTempName());

        $data = [
            'idpengguna' => $this->request->getPost('idpengguna'),
            'namawisata' => $this->request->getPost('namawisata'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'alamat'     => $this->request->getPost('alamat'),
            'idkategori' => $this->request->getPost('idkategori'),
            'foto'       => $fotoData,
        ];

        $save = $this->model->insert($data);

        if ($save) {
            return $this->respondCreated(['status' => 201, 'message' => 'Pengajuan berhasil']);
        } else {
            return $this->failServerError('Gagal menyimpan pengajuan');
        }
    }
}