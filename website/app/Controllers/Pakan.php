<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SensorUltrasonikModel;
use \Hermawan\DataTables\DataTable;

class Pakan extends BaseController
{
    protected $SensorUltrasonikModel;

    public function __construct()
    {
        $this->SensorUltrasonikModel = new SensorUltrasonikModel();
    }

    public function index()
    {
        return view("v_pemberian_pakan", [
            'title' => "Pemberian Pakan",
            'subtitle' => ""
        ]);
    }

    public function listData()
    {
        $db = db_connect();
        $builder = $db->table('tb_sensor_ultrasonik')->select('tanggal, jam, servo, presentase_pakan')
            ->where('servo', "ON")
            ->orderBy('id', 'ASC');
        return DataTable::of($builder)
            ->add('presentase_pakan', function ($row) {
                return $row->presentase_pakan . " %";
            })
            ->addNumbering('no')->filter(function ($builder, $request) {
                if ($request->tanggal)
                    $builder->where('tanggal', $request->tanggal);
            })
            ->toJson(true);
    }
}
