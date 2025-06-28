<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'idpengguna';

    protected $allowedFields = ['username', 'email', 'password', 'role'];
    public function findByUsernameOrEmail($login)
{
    return $this->where('username', $login)
                ->orWhere('email', $login)
                ->first();
}

}

