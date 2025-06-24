<?php

namespace App\Models;

use CodeIgniter\Model;

class EditModel extends Model
{
    protected $table            = 'pengajuan';        
    protected $primaryKey       = 'idpengajuan';           
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'namawisata',
        'deskripsi',
        'alamat',
        'foto',
        'idkategori',
        'idpengguna'  
    ];
}
