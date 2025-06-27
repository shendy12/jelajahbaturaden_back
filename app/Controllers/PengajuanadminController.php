<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PengajuanadminController extends ResourceController
{
    protected $modelName = 'App\Models\PengajuanadminModel';
    protected $format    = 'json';

    public function __construct()
    {
        // Load WisataModel untuk aksi 'posting'
        $this->wisataModel = new \App\Models\wisatamodel();
        // Load URL helper
        helper('url');
    }

    /**
     * Mengembalikan daftar semua resource
     *
     * @return mixed
     */
    public function index()
    {
        $data = $this->model->getPengajuanWithKategori();
        if ($data) {
            // Ubah field 'foto' menjadi URL lengkap yang bisa diakses client
            foreach ($data as &$item) {
                if (!empty($item['foto'])) {
                    $item['foto'] = base_url('uploads/' . $item['foto']);
                } else {
                    $item['foto'] = ''; // Kirim string kosong jika tidak ada foto
                }
            }
            return $this->respond($data);
        }
        return $this->failNotFound('Data pengajuan tidak ditemukan.');
    }

    /**
     * Mengembalikan detail dari satu resource
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->model->getDetailPengajuan($id);
        if ($data) {
            // Ubah field 'foto' menjadi URL lengkap
            if (!empty($data['foto'])) {
                $data['foto'] = base_url('uploads/' . $data['foto']);
            } else {
                $data['foto'] = '';
            }
            return $this->respond($data);
        }
        return $this->failNotFound('Pengajuan dengan ID ' . $id . ' tidak ditemukan.');
    }

    /**
     * Menghapus resource yang spesifik (Menolak pengajuan)
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $pengajuan = $this->model->find($id);
        if ($pengajuan) {
            // Hapus file gambar dari server jika ada
            if (!empty($pengajuan['foto'])) {
                $filePath = FCPATH . 'uploads/' . $pengajuan['foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // Hapus record dari database
            $this->model->delete($id);
            
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Pengajuan berhasil ditolak (dihapus).'
                ]
            ];
            return $this->respondDeleted($response);
        }
        return $this->failNotFound('Pengajuan dengan ID ' . $id . ' tidak ditemukan.');
    }

    /**
     * Fungsi custom untuk memindahkan data dari pengajuan ke wisata (Menerima pengajuan)
     *
     * @return mixed
     */
    public function posting($id = null)
    {
        // 1. Ambil data pengajuan berdasarkan ID
        $pengajuan = $this->model->find($id);

        if (!$pengajuan) {
            return $this->failNotFound('Pengajuan dengan ID ' . $id . ' tidak ditemukan.');
        }

        // 2. Siapkan data untuk dimasukkan ke tabel wisata
        // Menambahkan 'rating' dan 'tanggalposting' sesuai model baru
        $dataWisata = [
            'namawisata'     => $pengajuan['namawisata'],
            'deskripsi'      => $pengajuan['deskripsi'],
            'alamat'         => $pengajuan['alamat'],
            'idkategori'     => $pengajuan['idkategori'],
            'foto'           => $pengajuan['foto'], 
            'rating'         => 0, // Beri nilai default 0 untuk rating
            'tanggalposting' => date('Y-m-d H:i:s') // Isi dengan tanggal dan waktu saat ini
        ];
        
        // 3. Masukkan data ke tabel wisata
        if ($this->wisataModel->insert($dataWisata)) {
            // 4. Jika berhasil, hapus data dari tabel pengajuan
            // PENTING: Jangan hapus file fisiknya, karena sudah dipakai oleh tabel wisata
            $this->model->delete($id);

            $response = [
                'status'   => 201, // 201 Created
                'error'    => null,
                'messages' => [
                    'success' => 'Pengajuan berhasil diterima dan diposting sebagai wisata baru.'
                ]
            ];
            return $this->respondCreated($response);
        } else {
            return $this->fail('Gagal memposting wisata.', 500);
        }
    }
}
