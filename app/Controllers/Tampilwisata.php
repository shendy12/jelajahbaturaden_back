<?php

namespace App\Controllers;

use App\Models\WisataModel;
use CodeIgniter\RESTful\ResourceController;

class TampilWisata extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $model = new WisataModel();
        $data = $model
            ->select('wisata.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = wisata.idkategori')
            ->findAll();

        foreach ($data as &$item) {
            if (!empty($item['foto'])) {
                $item['foto'] = 'data:image/jpeg;base64,' . base64_encode($item['foto']);
            } else {
                $item['foto'] = null;
            }
        }

        return $this->respond($data);
    }

    public function byKategori($idkategori = null)
    {
        $model = new WisataModel();
        $data = $model
            ->select('wisata.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = wisata.idkategori')
            ->where('wisata.idkategori', $idkategori)
            ->findAll();

        foreach ($data as &$item) {
            if (!empty($item['foto'])) {
                $item['foto'] = 'data:image/jpeg;base64,' . base64_encode($item['foto']);
            } else {
                $item['foto'] = null;
            }
        }

        return $this->respond($data);
    }
}
