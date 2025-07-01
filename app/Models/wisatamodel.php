<?php

namespace App\Models;

use CodeIgniter\Model;

class WisataModel extends Model
{
    protected $table      = 'wisata';              
    protected $primaryKey = 'idwisata';            

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'namawisata',
        'deskripsi',
        'alamat',
        'foto',
        'rating',
        'idkategori',
        'tanggalposting'
        
    ];

    protected $useTimestamps = false;              
    protected $returnType    = 'array';            

    /**
     * Mengambil semua wisata dan join ke tabel kategori.
     * Jika $idkategori diberikan, maka akan filter by kategori.
     */
    public function getWithKategori($idkategori = null)
    {
        $builder = $this->db->table('wisata')
            ->select('wisata.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = wisata.idkategori');

        if ($idkategori !== null) {
            $builder->where('wisata.idkategori', $idkategori);
        }

        return $builder->get()->getResultArray();
    }

    public function getById($id)
    {
        return $this->where('idwisata', $id)->first();
    }

    public function getByKategori($idkategori)
    {
        return $this->where('idkategori', $idkategori)->findAll();
    }

    public function search($keyword)
    {
        return $this->like('namawisata', $keyword)
                    ->orLike('deskripsi', $keyword)
                    ->findAll();
    }
}
