<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JelajahBatur extends Migration
{
    public function up()
    {
        // Tabel Pengguna
        $this->forge->addField([
            'idpengguna' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'username'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => ['type' => 'VARCHAR', 'constraint' => 10],
        ]);
        $this->forge->addKey('idpengguna', true);
        $this->forge->createTable('pengguna');

        // Tabel Kategori
        $this->forge->addField([
            'idkategori'    => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'namakategori'  => ['type' => 'VARCHAR', 'constraint' => 300],
        ]);
        $this->forge->addKey('idkategori', true);
        $this->forge->createTable('kategori');

        // Tabel Wisata
        $this->forge->addField([
            'idwisata'      => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'namawisata'    => ['type' => 'VARCHAR', 'constraint' => 300],
            'deskripsi'     => ['type' => 'TEXT'],
            'alamat'        => ['type' => 'VARCHAR', 'constraint' => 300],
            'foto'          => ['type' => 'VARCHAR', 'constraint' => 255], 
            'rating'        => ['type' => 'TINYINT', 'constraint' => 1],
            'idkategori'    => ['type' => 'INT', 'constraint' => 11],
            'tanggalposting'=> ['type' => 'TIMESTAMP'],
        ]);
        $this->forge->addKey('idwisata', true);
        $this->forge->addForeignKey('idkategori', 'kategori', 'idkategori');
        $this->forge->createTable('wisata');

        // Tabel Favorit
        $this->forge->addField([
            'favoritID'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'idpengguna'  => ['type' => 'INT', 'constraint' => 11],
            'idwisata'    => ['type' => 'INT', 'constraint' => 11],
        ]);
        $this->forge->addKey('favoritID', true);
        $this->forge->addForeignKey('idpengguna', 'pengguna', 'idpengguna');
        $this->forge->addForeignKey('idwisata', 'wisata', 'idwisata');
        $this->forge->createTable('favorit');

        // Tabel Review
        $this->forge->addField([
            'idreview'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'idpengguna' => ['type' => 'INT', 'constraint' => 11],
            'idwisata'   => ['type' => 'INT', 'constraint' => 11],
            'review'     => ['type' => 'TEXT'],
            'date'       => ['type' => 'TIMESTAMP'],
            'rating'     => ['type' => 'INT', 'constraint' => 11],
            'foto'       => ['type' => 'VARCHAR', 'constraint' => 255], 
        ]);
        $this->forge->addKey('idreview', true);
        $this->forge->addForeignKey('idpengguna', 'pengguna', 'idpengguna');
        $this->forge->addForeignKey('idwisata', 'wisata', 'idwisata');
        $this->forge->createTable('review');

        // Tabel Pengajuan
        $this->forge->addField([
            'idpengajuan' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'idpengguna'  => ['type' => 'INT', 'constraint' => 11],
            'namawisata'  => ['type' => 'VARCHAR', 'constraint' => 300],
            'deskripsi'   => ['type' => 'TEXT'],
            'alamat'      => ['type' => 'VARCHAR', 'constraint' => 300],
            'foto'        => ['type' => 'VARCHAR', 'constraint' => 255], 
            'idkategori'  => ['type' => 'INT', 'constraint' => 11],
        ]);
        $this->forge->addKey('idpengajuan', true);
        $this->forge->addForeignKey('idpengguna', 'pengguna', 'idpengguna');
        $this->forge->addForeignKey('idkategori', 'kategori', 'idkategori');
        $this->forge->createTable('pengajuan');

        // Tabel Pencarian
        $this->forge->addField([
            'idpencarian' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'idpengguna'  => ['type' => 'INT', 'constraint' => 11],
            'date'        => ['type' => 'TIMESTAMP'],
            'text'        => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('idpencarian', true);
        $this->forge->addForeignKey('idpengguna', 'pengguna', 'idpengguna');
        $this->forge->createTable('pencarian');
    }

    public function down()
    {
        $this->forge->dropTable('pencarian');
        $this->forge->dropTable('pengajuan');
        $this->forge->dropTable('review');
        $this->forge->dropTable('favorit');
        $this->forge->dropTable('wisata');
        $this->forge->dropTable('kategori');
        $this->forge->dropTable('pengguna');
    }
}