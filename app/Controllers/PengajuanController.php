<?php

namespace App\Controllers;

use App\Models\PengajuanModel;
use CodeIgniter\RESTful\ResourceController;

class PengajuanController extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';

    public function create()
    {
        helper(['form']);
        $validationRules = [
            'idpengguna' => 'required|integer',
            'namawisata' => 'required',
            'deskripsi'  => 'required',
            'alamat'     => 'required',
            'idkategori' => 'required|integer',
            'foto'       => 'uploaded[foto]|is_image[foto]|max_size[foto,5120]', // max 5MB
        ];

        if (!$this->validate($validationRules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Simpan data awal tanpa foto
        $model = new PengajuanModel();

        $data = [
            'idpengguna' => $this->request->getPost('idpengguna'),
            'namawisata' => $this->request->getPost('namawisata'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'alamat'     => $this->request->getPost('alamat'),
            'idkategori' => $this->request->getPost('idkategori'),
            'foto'       => '', // sementara kosong, akan diupdate setelah upload
        ];

        $model->insert($data);
        $idpengajuan = $model->getInsertID(); // ambil id terakhir

        // Tangani file foto
        $foto = $this->request->getFile('foto');
        $ekstensi = $foto->getExtension(); // jpg/png
        $namaFoto = 'pengajuan_' . $idpengajuan . '.' . $ekstensi;

        $uploadPath = ROOTPATH . 'public/uploads';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $foto->move($uploadPath, $namaFoto);

        // Update nama foto ke database
        $model->update($idpengajuan, ['foto' => $namaFoto]);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Pengajuan berhasil disimpan',
            'data'    => [
                'idpengajuan' => $idpengajuan,
                'foto'        => base_url('uploads/' . $namaFoto),
            ]
        ]);
    }
}