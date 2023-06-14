<?php

namespace App\Models;

use CodeIgniter\Model;

class SensorSuhuModel extends Model
{
    protected $table            = 'tb_sensor_suhu';
    protected $primaryKey       = 'id';
    protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'jam', 'data_sensor', 'status_suhu', 'penghangat_akuarium'];

    public function getData()
    {
        return $this->table($this->table)->orderBy('id', 'DESC')->limit(1)->get();
    }
}
