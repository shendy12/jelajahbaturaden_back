<?php

namespace App\Controllers;

use App\Models\WisataModel;
use CodeIgniter\RESTful\ResourceController;

class WisataController extends ResourceController
{
    protected $format = 'json';

    // GET /wisata
    public function index()
    {
        $model = new WisataModel();
        $data = $model->getWithKategori();

        foreach ($data as &$item) {
            $item['foto'] = base_url('uploads/' . $item['foto']);
        }

        return $this->respond($data);
    }

    // GET /wisata/kategori/(:id)
    public function byKategori($idkategori = null)
    {
        $model = new WisataModel();
        $data = $model->getWithKategori($idkategori);

        foreach ($data as &$item) {
            $item['foto'] = base_url('uploads/' . $item['foto']);
        }

        return $this->respond($data);
    }

    // POST /wisata
    public function create()
    {
        $model = new WisataModel();

        $validationRule = [
            'namawisata' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'idkategori' => 'required|integer',
            'foto' => 'uploaded[foto]|max_size[foto,5120]|is_image[foto]',
        ];

        if (!$this->validate($validationRule)) {
            return $this->fail($this->validator->getErrors());
        }

        // Data awal (belum ada nama file foto karena belum tahu id)
        $data = [
            'namawisata' => $this->request->getPost('namawisata'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'idkategori' => $this->request->getPost('idkategori'),
            'foto' => '', // sementara kosong
            'rating' => 0,
            'tanggalposting' => date('Y-m-d H:i:s')
        ];

        // Insert sementara tanpa foto
        $model->insert($data);
        $idwisata = $model->getInsertID(); // ambil ID yang baru saja dibuat

        // Simpan foto dengan nama berdasarkan idwisata
        $foto = $this->request->getFile('foto');
        $ekstensi = $foto->getExtension(); // jpg/png/dll
        $namaFoto = $idwisata . '.' . $ekstensi;

        $uploadPath = ROOTPATH . 'public/uploads';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $foto->move($uploadPath, $namaFoto);

        // Update field foto
        $model->update($idwisata, ['foto' => $namaFoto]);

        return $this->respondCreated(['message' => 'Wisata berhasil ditambahkan']);
    }
}
