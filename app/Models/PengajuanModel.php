<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table      = 'pengajuan';
    protected $primaryKey = 'idpengajuan';

    protected $allowedFields = [
        'idpengguna',
        'namawisata',
        'deskripsi',
        'alamat',
        'foto',   
        'idkategori'
    ];

    protected $useTimestamps = false;
}