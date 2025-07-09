<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use CodeIgniter\API\ResponseTrait;

class ReviewController extends BaseController
{
    use ResponseTrait;

    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        helper(['date', 'session']);
    }

    /** 
     * GET /review/{idwisata}
     * Menampilkan semua ulasan dari wisata tertentu
     */
    public function index($idwisata = null)
    {
        if (empty($idwisata)) {
            return $this->fail('ID wisata diperlukan', 400);
        }
    
        $reviews = $this->reviewModel
            ->select('review.*, pengguna.username, pengguna.idpengguna AS id_pengguna')
            ->join('pengguna', 'pengguna.idpengguna = review.idpengguna')
            ->where('review.idwisata', $idwisata)
            ->orderBy('review.date', 'DESC')
            ->findAll();
    
        if (empty($reviews)) {
            return $this->respond([
                'message' => 'Belum ada ulasan untuk wisata ini',
                'data' => [],
            ], 200);
        }
    
        return $this->respond($reviews, 200);
    }
    
    /**
     * POST /review
     * Menyimpan ulasan baru dari pengguna
     * JSON: { "idwisata": 1, "review": "Komentar", "rating": 5, "id_pengguna": 1 }
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        // Validasi data
        if (!isset($data['idwisata'], $data['review'], $data['rating'], $data['id_pengguna'])) {
            return $this->fail('idwisata, review, rating, dan id_pengguna wajib diisi', 400);
        }

        $reviewData = [
            'idpengguna' => (int) $data['id_pengguna'], // sesuai kolom di tabel
            'idwisata'   => (int) $data['idwisata'],
            'review'     => trim($data['review']),
            'rating'     => (int) $data['rating'],
            'date'       => date('Y-m-d H:i:s'),
        ];

        if (!$this->reviewModel->insert($reviewData)) {
            return $this->failServerError('Gagal menyimpan ulasan');
        }

        return $this->respondCreated(['message' => 'Ulasan berhasil ditambahkan']);
    }

    /**
     * GET /review/rerata/{idwisata}
     * Mengembalikan rata-rata rating dan jumlah review untuk wisata
     */
    public function rerata($idwisata = null)
    {
        if (empty($idwisata)) {
            return $this->fail('ID wisata diperlukan', 400);
        }

        $average = $this->reviewModel
            ->where('idwisata', $idwisata)
            ->selectAvg('rating')
            ->first();

        $count = $this->reviewModel
            ->where('idwisata', $idwisata)
            ->countAllResults();

        return $this->respond([
            'idwisata' => (int) $idwisata,
            'average_rating' => round((float) $average['rating'], 1),
            'jumlah' => $count,
        ]);
    }

    /**
     * DELETE /review/{idreview}
     */
    public function delete($idreview = null)
    {
        if (!$idreview) {
            return $this->fail('ID review diperlukan', 400);
        }

        $deleted = $this->reviewModel->delete($idreview);
        if (!$deleted) {
            return $this->failNotFound('Ulasan tidak ditemukan atau gagal dihapus');
        }

        return $this->respondDeleted(['message' => 'Ulasan berhasil dihapus']);
    }

    public function update($idreview = null)
    {
        if (!$idreview) {
            return $this->fail('ID review diperlukan', 400);
        }

        $data = $this->request->getJSON(true);

        if (!isset($data['review']) || !isset($data['rating'])) {
            return $this->fail('review dan rating wajib diisi', 400);
        }

        $updateData = [
            'review' => trim($data['review']),
            'rating' => (int) $data['rating'],
        ];

        $updated = $this->reviewModel->update($idreview, $updateData);

        if (!$updated) {
            return $this->failServerError('Gagal mengupdate ulasan');
        }

        return $this->respond(['message' => 'Ulasan berhasil diperbarui'], 200);
    }
}
