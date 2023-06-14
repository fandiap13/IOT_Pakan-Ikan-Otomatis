<?php

namespace App\Models;

use CodeIgniter\Model;

class SistemModel extends Model
{
    protected $table            = 'tb_sistem';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_kategori_ikan', 'panjang_wadah', 'jarak_sensor', 'durasi_pakan'];

    public function getKategoriIkan()
    {
        return $this->table($this->table)->select("tb_kategori_ikan.*")
            ->join('tb_kategori_ikan', $this->table . ".id_kategori_ikan=tb_kategori_ikan.id")
            ->orderBy('id', 'DESC')->limit(1)->get();
    }
}
