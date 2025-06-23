<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WisataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idwisata'       => 5,
                'namawisata'     => 'curug jenggala',
                'deskripsi'      => 'bagus',
                'alamat'         => 'jenggala',
                'foto'           => '1750665799_362d29794960a8b87a6b.jpg',
                'rating'         => 0,
                'idkategori'     => 1,
                'tanggalposting' => '2025-06-23 08:03:19',
            ],
            [
                'idwisata'       => 6,
                'namawisata'     => 'curug jenggala',
                'deskripsi'      => 'bagus',
                'alamat'         => 'jenggala',
                'foto'           => '1750665818_1f8591d3f0b30b6b13e7.jpg',
                'rating'         => 0,
                'idkategori'     => 1,
                'tanggalposting' => '2025-06-23 08:03:38',
            ],
            [
                'idwisata'       => 7,
                'namawisata'     => 'curug jenggala',
                'deskripsi'      => 'bagus',
                'alamat'         => 'jenggala',
                'foto'           => '1750665825_88957f5945afb510e7da.jpg',
                'rating'         => 0,
                'idkategori'     => 1,
                'tanggalposting' => '2025-06-23 08:03:45',
            ],
            [
                'idwisata'       => 8,
                'namawisata'     => 'tes',
                'deskripsi'      => 'tes',
                'alamat'         => 'tes',
                'foto'           => '8.jpg',
                'rating'         => 0,
                'idkategori'     => 1,
                'tanggalposting' => '2025-06-23 18:29:47',
            ],
        ];

        // Insert data ke tabel wisata
        $this->db->table('wisata')->insertBatch($data);
    }
}
