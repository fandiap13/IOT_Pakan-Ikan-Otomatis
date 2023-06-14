<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjadwalanModel extends Model
{
    protected $table            = 'tb_penjadwalan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['jam', 'terakhir_aktif'];
}
