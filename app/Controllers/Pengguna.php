<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Pengguna extends ResourceController
{
    protected $format = 'json';
    
    protected $modelName = 'App\Models\PenggunaModel';

    public function add()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'username' => 'required|is_unique[pengguna.username]',
            'email'    => 'required|valid_email|is_unique[pengguna.email]',
            'password' => 'required|min_length[8]'
        ];

        $messages = [
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username sudah terdaftar.'
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.'
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal harus 8 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->model->insert([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $hashedPassword,
            'role' => $data['role'] ?? 'pengguna'
        ]);
        
        return $this->respondCreated(['message' => 'Pendaftaran berhasil']);
    }

    public function login()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'login'    => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Username/Email dan password wajib diisi.'
            ], 400);
        }

        $identifier = $json['login'];
        $password = $json['password'];

        $pengguna = $this->model->findByUsernameOrEmail($identifier);

        if (!$pengguna) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Username atau Email tidak terdaftar.'
            ], 404);
        }

        if (!password_verify($password, $pengguna['password'])) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Password salah.'
            ], 401);
        }

        unset($pengguna['password']); 

        return $this->respond([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => $pengguna,
            'role'    => $pengguna['role'] ?? 'user'
            
        ]);
    }
    public function resetPassword()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'email'        => 'required|valid_email',
            'new_password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $data['email'];
        $passwordBaru = $data['new_password'];

        $pengguna = $this->model->where('email', $email)->first();

        if (!$pengguna) {
            return $this->failNotFound('Email tidak ditemukan');
        }

        $this->model->update($pengguna['idpengguna'], [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        return $this->respond(['message' => 'Password berhasil direset']);
    }
}
