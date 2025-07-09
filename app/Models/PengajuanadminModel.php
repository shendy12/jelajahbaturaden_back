<?php
// File: app/Models/PengajuanModel.php
namespace App\Models;

use CodeIgniter\Model;

class PengajuanadminModel extends Model
{
    protected $table            = 'pengajuan';
    protected $primaryKey       = 'idpengajuan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['namawisata', 'deskripsi', 'alamat', 'idkategori', 'foto'];

    // Dates
    protected $useTimestamps = false;

    /**
     * Mengambil semua data pengajuan dengan join ke tabel kategori.
     */
    public function getPengajuanWithKategori()
    {
        return $this->db->table('pengajuan as p')
            ->select('p.idpengajuan, p.namawisata, k.namakategori, p.foto')
            ->join('kategori as k', 'k.idkategori = p.idkategori')
            ->get()
            ->getResultArray();
    }

    /**
     * Mengambil detail satu pengajuan dengan join ke tabel kategori.
     */
    public function getDetailPengajuan($id)
    {
        return $this->db->table('pengajuan as p')
            ->select('p.idpengajuan, p.namawisata, p.deskripsi, p.alamat, k.namakategori, p.foto, p.idkategori')
            ->join('kategori as k', 'k.idkategori = p.idkategori')
            ->where('p.idpengajuan', $id)
            ->get()
            ->getRowArray();
    }
}

