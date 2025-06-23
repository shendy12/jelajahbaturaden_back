<?php

namespace App\Controllers;

use App\Models\FavoritModel;
use CodeIgniter\RESTful\ResourceController;

class Favorit extends ResourceController
{
    protected $favoriteModel;

    public function __construct()
    {
        $this->favoriteModel = new FavoritModel();
    }

    // Ambil daftar favorit berdasarkan session atau query
    public function getUserFavorites()
    {
        $session = session();
        $request = service('request');

        // Coba ambil dari session
        $idpengguna = $session->get('idpengguna');

        // Jika tidak tersedia, ambil dari parameter URL (Flutter)
        if (!$idpengguna) {
            $idpengguna = $request->getGet('idpengguna');
        }

        if (!$idpengguna) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'unauthorized',
                'message' => 'ID pengguna tidak ditemukan.',
            ]);
        }

        try {
            $data = $this->favoriteModel->getFavoritesByUser($idpengguna);
        
            foreach ($data as &$item) {
                if (!empty($item->foto)) {
                    $item->foto = base_url('uploads/' . $item->foto);
                }
            }
        
            return $this->response->setStatusCode(200)->setJSON($data);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal ambil data favorit: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan di server.',
            ]);
        }
        
    }

    // Tambah atau hapus favorit berdasarkan request JSON
    public function toggleFavorite()
    {
        $request = service('request');
        $data = $request->getJSON();

        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Data tidak valid (bukan JSON)']);
        }

        $idpengguna = $data->idpengguna ?? null;
        $idwisata = $data->idwisata ?? null;
        $action = $data->action ?? '';

        if (!$idpengguna || !$idwisata || !in_array($action, ['add', 'remove'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Parameter tidak lengkap']);
        }

        try {
            if ($action === 'add') {
                // Hindari duplikat
                if (!$this->favoriteModel->isFavorited($idpengguna, $idwisata)) {
                    $this->favoriteModel->addFavorite($idpengguna, $idwisata);
                }
            } else {
                $this->favoriteModel->removeFavorite($idpengguna, $idwisata);
            }

            return $this->response->setStatusCode(200)->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal toggle favorit: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
