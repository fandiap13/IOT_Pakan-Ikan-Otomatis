<?php

namespace App\Models;

use CodeIgniter\Model;

class SensorUltrasonikModel extends Model
{
    protected $table            = 'tb_sensor_ultrasonik';
    protected $primaryKey       = 'id';
    protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'jam', 'data_sensor', 'status_pakan', 'presentase_pakan', 'buzzer', 'servo', 'status_pemberian_pakan'];

    public function getData()
    {
        return $this->table($this->table)->orderBy('id', 'DESC')->limit(1)->get();
    }
}
