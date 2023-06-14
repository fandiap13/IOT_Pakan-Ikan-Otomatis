<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriIkanModel extends Model
{
    protected $table            = 'tb_kategori_ikan';
    protected $primaryKey       = 'id';
    protected $protectFields    = true;
    protected $allowedFields    = ['kategori', 'suhu_min', 'suhu_max'];
}
