<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoritModel extends Model
{
    protected $table = 'favorit';
    protected $allowedFields = ['idpengguna', 'idwisata'];
    public $timestamps = false;

    public function getFavoritesByUser($idpengguna)
    {
        return $this->db->table('favorit f')
            ->select('w.idwisata, w.namawisata, w.foto, k.namakategori')
            ->join('wisata w', 'w.idwisata = f.idwisata')
            ->join('kategori k', 'k.idkategori = w.idkategori')
            ->where('f.idpengguna', $idpengguna)
            ->get()->getResult();
    }

    public function isFavorited($idpengguna, $idwisata)
    {
        return $this->where(['idpengguna' => $idpengguna, 'idwisata' => $idwisata])->first();
    }

    public function addFavorite($idpengguna, $idwisata)
    {
        return $this->insert(['idpengguna' => $idpengguna, 'idwisata' => $idwisata]);
    }

    public function removeFavorite($idpengguna, $idwisata)
    {
        return $this->where(['idpengguna' => $idpengguna, 'idwisata' => $idwisata])->delete();
    }
}
