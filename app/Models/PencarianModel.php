<?php
namespace App\Models;

use CodeIgniter\Model;

class PencarianModel extends Model
{
    protected $table = 'pencarian';
    protected $primaryKey = 'idpencarian';
    protected $allowedFields = ['idpengguna', 'date', 'text'];
}
