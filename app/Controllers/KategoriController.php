<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use CodeIgniter\RESTful\ResourceController;

class KategoriController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $model = new KategoriModel();
        $data = $model->findAll();

        return $this->respond($data);
    }
}
