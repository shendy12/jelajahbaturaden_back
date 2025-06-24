<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'review';            // Nama tabel di database
    protected $primaryKey       = 'idreview';          // Primary key tabel
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'idpengguna',
        'idwisata',
        'review',
        'rating',
        'date',
        'foto' // Opsional jika nanti kamu menambahkan upload foto
    ];

    protected $useTimestamps = false; // Tidak pakai created_at/updated_at default CI
}