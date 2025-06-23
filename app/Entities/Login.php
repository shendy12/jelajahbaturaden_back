<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Login extends Entity
{
    protected $datamap = [
        // 'emailAddress' => 'email', // contoh pemetaan jika perlu
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'selected_at', // pastikan field ini memang ada
    ];

    protected $casts = [
        'id_pengguna' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    public function setPassword(string $pass)
    {
        $this->attributes['password'] = password_hash($pass, PASSWORD_DEFAULT);
        return $this;
    }
}
