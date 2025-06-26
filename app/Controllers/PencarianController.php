<?php

namespace App\Controllers;

use App\Models\PencarianModel;
use App\Models\WisataModel;
use CodeIgniter\RESTful\ResourceController;

class PencarianController extends ResourceController
{
    protected $format = 'json';

    public function search()
    {
        $idpengguna = $this->request->getPost('idpengguna');
        $text = $this->request->getPost('text');

        if (!$idpengguna || !$text) {
            return $this->failValidationError('idpengguna dan text diperlukan');
        }

        // Simpan ke history pencarian
        $pencarianModel = new PencarianModel();
        $pencarianModel->insert([
            'idpengguna' => $idpengguna,
            'date' => date('Y-m-d H:i:s'),
            'text' => $text,
        ]);

        // Cari wisata
        $wisataModel = new WisataModel();
        $result = $wisataModel->like('namawisata', $text)->findAll();

        return $this->respond($result);
    }

    public function history($idpengguna)
    {
        $pencarianModel = new PencarianModel();
        $history = $pencarianModel
                    ->where('idpengguna', $idpengguna)
                    ->orderBy('date', 'DESC')
                    ->findAll(10); // Batasi 10 terbaru

        return $this->respond($history);
    }
}
