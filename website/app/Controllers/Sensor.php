<?php

// BAGIAN YANG MENGAMBIL DATA SENSOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SensorSuhuModel;
use App\Models\SensorUltrasonikModel;
use App\Models\SistemModel;

use \Hermawan\DataTables\DataTable;

class Sensor extends BaseController
{
    protected $SensorUltrasonikModel;
    protected $SensorSuhuModel;
    protected $SistemModel;

    public function __construct()
    {
        $this->SensorUltrasonikModel = new SensorUltrasonikModel();
        $this->SensorSuhuModel = new SensorSuhuModel();
        $this->SistemModel = new SistemModel();
    }

    public function data_sensor()
    {
        if ($this->request->isAJAX()) {
            try {
                $getUltrasonic = $this->SensorUltrasonikModel->getData()->getRowArray();
                $getSuhu = $this->SensorSuhuModel->getData()->getRowArray();
                $kategori_ikan = $this->SistemModel->getKategoriIkan()->getRowArray();

                if ($getUltrasonic['presentase_pakan'] > 50 && $getUltrasonic['presentase_pakan'] <= 100) {
                    $status_pakan = "Pakan masih ada";
                } else if ($getUltrasonic['presentase_pakan'] > 30 && $getUltrasonic['presentase_pakan'] <= 50) {
                    $status_pakan = "Pakan tinggal setengah";
                } else if ($getUltrasonic['presentase_pakan'] > 10 && $getUltrasonic['presentase_pakan'] <= 30) {
                    $status_pakan = "Pakan hampir habis";
                } else if ($getUltrasonic['presentase_pakan'] <= 10) {
                    $status_pakan = "Pakan sudah habis";
                } else {
                    $status_pakan = "Pakan Overload !!!";
                }

                $json = [
                    'data' => [
                        'suhu' => [
                            'suhu_akuarium' => $getSuhu['data_sensor'],
                            'tanggal' => $getSuhu['tanggal'],
                            'jam' => $getSuhu['jam'],
                            'status_suhu' => $getSuhu['status_suhu'],
                            'penghangat_akuarium' => $getSuhu['penghangat_akuarium']
                        ],
                        'pakan' => [
                            'presentase_pakan' => $getUltrasonic['presentase_pakan'],
                            'tanggal' => $getUltrasonic['tanggal'],
                            'jam' => $getUltrasonic['jam'],
                            'status_pakan' => $status_pakan,
                            'status_buzzer' => $getUltrasonic['buzzer']
                        ],
                        'ikan' => $kategori_ikan
                    ]
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => "Terdapat kesalahan pada sistem"
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function data_grafik()
    {
        // $dataPakan = $this->SensorUltrasonikModel->select('AVG(presentase_pakan) as data, tanggal, jam')->groupBy('tanggal')->orderBy('tanggal', 'asc')->get()->getResultArray();
        // dd($dataPakan);
        if ($this->request->isAJAX()) {

            $bulan_pakan = $this->request->getPost('bulan_pakan');
            $bulan_suhu = $this->request->getPost('bulan_suhu');

            $tanggal_suhu = [];
            $data_suhu = [];
            $tanggal_pakan = [];
            $data_pakan = [];

            $dataSuhu = $this->SensorSuhuModel
                ->select('AVG(data_sensor) as data, tanggal, jam')
                ->where('DATE_FORMAT(tanggal, "%Y-%m")', $bulan_suhu)
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get()->getResultArray();
            foreach ($dataSuhu as $s) {
                $tanggal_suhu[] = $s['tanggal'];
                $data_suhu[] = round($s['data'], 2);
            }

            $dataPakan = $this->SensorUltrasonikModel
                ->select('AVG(presentase_pakan) as data, tanggal, jam')
                ->where('DATE_FORMAT(tanggal, "%Y-%m")', $bulan_pakan)
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get()->getResultArray();
            foreach ($dataPakan as $p) {
                $tanggal_pakan[] = $p['tanggal'];
                $data_pakan[] = round($p['data'], 2);
            }

            // dd($data_pakan);

            $json = [
                'data' => [
                    'grafik_suhu' => [
                        'tanggal' => $tanggal_suhu,
                        'data_sensor' => $data_suhu
                    ],
                    'grafik_pakan' => [
                        'tanggal' => $tanggal_pakan,
                        'data_sensor' => $data_pakan
                    ]
                ]
            ];
            echo json_encode($json);
        } else {
            exit("Tidak dapat diakses");
        }
    }

    // data sensor dan datatabel serverside
    public function sensor_jarak()
    {
        return view('v_sensor_jarak', [
            'title' => 'Data Sensor',
            'subtitle' => 'Sensor Jarak'
        ]);
    }

    public function sensor_suhu()
    {
        return view('v_sensor_suhu', [
            'title' => 'Data Sensor',
            'subtitle' => 'Sensor Suhu'
        ]);
    }

    public function list_sensor_jarak()
    {
        $db = db_connect();
        $builder = $db->table('tb_sensor_ultrasonik')->select('tanggal, jam, data_sensor, presentase_pakan, servo, buzzer')->orderBy('id', 'DESC');
        return DataTable::of($builder)
            ->add('presentase_pakan', function ($row) {
                return $row->presentase_pakan . " %";
            })
            ->addNumbering('no')
            ->toJson(true);
    }

    public function list_sensor_suhu()
    {
        $db = db_connect();
        $builder = $db->table('tb_sensor_suhu')->select('tanggal, jam, data_sensor, status_suhu, penghangat_akuarium')->orderBy('id', 'DESC');
        return DataTable::of($builder)
            ->add('data_sensor', function ($row) {
                return $row->data_sensor . " °C";
            })
            ->addNumbering('no')
            ->toJson(true);
    }
}
