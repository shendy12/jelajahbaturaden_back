<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Favorit extends ResourceController
{protected $favoriteModel;

    public function __construct()
    {
        $this->favoriteModel = new FavoriteModel();
    }

    // Ambil daftar favorit berdasarkan session pengguna
    public function getUserFavorites()
    {
        $session = session();
        $idpengguna = $session->get('idpengguna');

        if (!$idpengguna) {
            return $this->response->setJSON([
                'status' => 'unauthorized',
                'message' => 'Pengguna belum login.'
            ]);
        }

        $data = $this->favoriteModel->getFavoritesByUser($idpengguna);

        return $this->response->setJSON($data);
    }

    // Tambah atau hapus favorit
    public function toggleFavorite()
    {
        $session = session();
        $idpengguna = $session->get('idpengguna');

        if (!$idpengguna) {
            return $this->response->setJSON([
                'status' => 'unauthorized',
                'message' => 'Pengguna belum login.'
            ]);
        }

        $idwisata = $this->request->getPost('idwisata');

        if (!$idwisata) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID wisata tidak ditemukan.'
            ]);
        }

        // Cek apakah wisata ini sudah difavoritkan
        if ($this->favoriteModel->isFavorited($idpengguna, $idwisata)) {
            // Jika sudah, hapus
            $this->favoriteModel->removeFavorite($idpengguna, $idwisata);
            return $this->response->setJSON([
                'status' => 'removed',
                'message' => 'Wisata dihapus dari favorit.'
            ]);
        } else {
            // Jika belum, tambahkan
            $this->favoriteModel->addFavorite($idpengguna, $idwisata);
            return $this->response->setJSON([
                'status' => 'added',
                'message' => 'Wisata ditambahkan ke favorit.'
            ]);
        }
    }
}
