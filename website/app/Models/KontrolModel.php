<?php

namespace App\Models;

use CodeIgniter\Model;

class KontrolModel extends Model
{
    protected $table            = 'tb_control';
    protected $primaryKey       = 'id';
    protected $protectFields    = true;
    protected $allowedFields    = ['saklar', 'buzzer', 'servo', 'manual_buzzer', 'status_pemberian_pakan', 'penghangat_akuarium'];

    public function getDataKontrol()
    {
        return $this->table($this->table)->orderBy('id', 'DESC')->limit(1)->get();
    }
}
