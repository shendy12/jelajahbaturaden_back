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

        // Ambil semua review berdasarkan id_wisata
        $reviews = $this->reviewModel
            ->select('review.*, pengguna.username')
            ->join('pengguna', 'pengguna.id_pengguna = review.id_pengguna')
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
     * JSON: { "idwisata": 1, "review": "Komentar", "rating": 5 }
     */
    public function create()
    {
        $session = session();
        $id_pengguna = $session->get('id_pengguna');

        // Untuk keperluan development, jika session belum ada, bisa di-hardcode sementara:
        // $id_pengguna = 1;

        if (!$id_pengguna) {
            return $this->failUnauthorized('Login diperlukan untuk memberi ulasan');
        }

        $data = $this->request->getJSON(true);

        if (!isset($data['idwisata'], $data['review'], $data['rating'])) {
            return $this->fail('idwisata, review, dan rating wajib diisi', 400);
        }

        $reviewData = [
            'id_pengguna' => $id_pengguna,
            'idwisata'    => (int) $data['idwisata'],
            'review'      => trim($data['review']),
            'rating'      => (int) $data['rating'],
            'date'        => date('Y-m-d H:i:s'),
        ];

        if (!$this->reviewModel->insert($reviewData)) {
            return $this->failServerError('Gagal menyimpan ulasan');
        }

        return $this->respondCreated(['message' => 'Ulasan berhasil ditambahkan']);
    }

    /**
     * GET /review/rerata/{idwisata}
     * Mengembalikan rata-rata rating dan jumlah review untuk sebuah wisata
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
}