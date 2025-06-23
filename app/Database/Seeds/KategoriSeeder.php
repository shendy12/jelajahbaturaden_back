<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['namakategori' => 'Wisata Alam'],
            ['namakategori' => 'Budaya'],
            ['namakategori' => 'Kuliner'],
            ['namakategori' => 'Lainnya'],
        ];

        $this->db->table('kategori')->insertBatch($data);
    }
}