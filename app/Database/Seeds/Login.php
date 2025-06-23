<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Login extends Seeder
{
    public function run()
    {
        $data = [
        ['email' =>'shendyf97@gmail.com' ,
        'password' =>'test',
        'username' =>'shendy',
        'role' => "admin",
        ],
        ['email' =>'budi@example.com' ,
        'password' =>'test',
        'username' =>'budi',
        'role' => "pengguna",
        ],
        
        ['email' =>'andi@example.com' ,
        'password' =>'test',
        'username' =>'andi',
        'role' => "pengguna",
        ]
        ];

        $this->db->table('pengguna')->insertBatch($data);
    }
}
