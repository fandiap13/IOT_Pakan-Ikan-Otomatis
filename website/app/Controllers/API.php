<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KontrolModel;
use App\Models\PenjadwalanModel;
use App\Models\SensorSuhuModel;
use App\Models\SensorUltrasonikModel;
use App\Models\SistemModel;
use CodeIgniter\API\ResponseTrait;

class API extends BaseController
{
    use ResponseTrait;

    protected $SensorSuhuModel;
    protected $SensorUltrasonikModel;
    protected $SistemModel;
    protected $KontrolModel;
    protected $tanggal;
    protected $jam;
    protected $id_kontrol;
    protected $PASS;
    protected $PenjadwalanModel;

    public function __construct()
    {
        // model tabel
        $this->SensorSuhuModel = new SensorSuhuModel();
        $this->SensorUltrasonikModel = new SensorUltrasonikModel();
        $this->SistemModel = new SistemModel();
        $this->KontrolModel = new KontrolModel();
        $this->PenjadwalanModel = new PenjadwalanModel();

        // jam dan tanggal
        $this->tanggal = date("Y-m-d");
        $this->jam = date('H:i:s');

        // id dari tabel
        $this->id_kontrol = $this->KontrolModel->select('id')->orderBy('id', 'DESC')->limit(1)
            ->get()->getRowArray()['id'];

        $this->PASS = "DENIMANUSIAIKAN";
    }

    public function ambil_data_kontrol($PASS = null)
    {
        if ($PASS != $this->PASS) {
            return $this->respond([
                'error' => "Pass is incorrect"
            ], 401);
        }

        try {
            // $jam = "10:00";
            // $tanggal = "2023-05-31";

            $jam = date('H:i');
            $tanggal = date("Y-m-d");
            // cari jadwal berdasarkan jam
            $penjadwalan = $this->PenjadwalanModel->where("jam", $jam)->get()->getRowArray();
            if ($penjadwalan) {
                // jika jadwal hari ini belum diaktifkan maka jalankan pemberian pakan
                if ($penjadwalan['terakhir_aktif'] != $tanggal) {
                    $this->PenjadwalanModel->update($penjadwalan['id'], [
                        'terakhir_aktif' => $tanggal
                    ]);
                    $this->KontrolModel->update($this->id_kontrol, [
                        'servo' => 'ON'
                    ]);
                }
            }

            $data_kontrol = $this->KontrolModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
            $data_sistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
            $data_sensor_jarak = $this->SensorUltrasonikModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();

            $data = [
                'buzzer' => $data_kontrol['buzzer'],
                'servo' => $data_kontrol['servo'],
                'penghangat_akuarium' => $data_kontrol['penghangat_akuarium'],
                'status_pemberian_pakan' => $data_kontrol['status_pemberian_pakan'],
                'durasi_pakan' => $data_sistem["durasi_pakan"],
                'presentase_pakan' => $data_sensor_jarak['presentase_pakan'],
            ];

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            return $this->respond([
                'error' => "Terdapat kesalahan pada sistem"
            ], 500);
        }
    }

    public function tambah_data($PASS = null)
    {
        if ($PASS != $this->PASS) {
            return $this->respond([
                'error' => "Pass is incorrect"
            ], 401);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'sensor_ultrasonik' => [
                'label' => 'Sensor Ultrasonik',
                'rules' => "required|numeric",
                'errors' => [
                    'required' => "{field} tidak boleh kosong",
                    'numeric' => "{field} harus berbentuk angka"
                ]
            ],
            'sensor_suhu' => [
                'label' => 'Sensor Suhu',
                'rules' => "required|numeric",
                'errors' => [
                    'required' => "{field} tidak boleh kosong",
                    'numeric' => "{field} harus berbentuk angka"
                ]
            ]
        ]);

        if (!$validation->run($this->request->getPost())) {
            return $this->respond([
                'errors' => [
                    'sensor_ultrasonik' => $validation->getError('sensor_ultrasonik'),
                    'sensor_suhu' => $validation->getError('sensor_suhu')
                ]
            ], 400);
        }

        // -------------------------------- SENSOR SUHU -------------------------------- 
        $sensor_suhu = $this->request->getPost('sensor_suhu');
        $dataSistem = $this->SistemModel->getKategoriIkan()->getRowArray();
        if ($sensor_suhu >= $dataSistem['suhu_min'] && $sensor_suhu <= $dataSistem['suhu_max']) {
            $status_suhu = "Suhu stabil";
            $penghangat_akuarium = "OFF";
        } else if ($sensor_suhu < $dataSistem['suhu_min']) {
            $status_suhu = "Akuarium dingin, Suhu untuk " . $dataSistem['kategori'] . " adalah " . $dataSistem['suhu_min'] . "°C s/d " . $dataSistem['suhu_max'] . " °C";
            $penghangat_akuarium = "ON";
        } else {
            $status_suhu = "Akuarium panas, Suhu untuk " . $dataSistem['kategori'] . " adalah " . $dataSistem['suhu_min'] . "°C s/d " . $dataSistem['suhu_max'] . " °C";
            $penghangat_akuarium = "OFF";
        }

        // -------------------------------- SENSOR ULTRASONIK -------------------------------- 
        $sensor_ultrasonik = (float) $this->request->getPost('sensor_ultrasonik');
        $status_pemberian_pakan = $this->request->getPost("status_pemberian_pakan");

        // mengambil data tabel sistem panjang wadah dan jarak antar wadah ke sensor
        $dataSistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        $panjang_wadah = $dataSistem['panjang_wadah'];
        $jarak_sensor = $dataSistem['jarak_sensor'];

        $volume_wadah = floatval($panjang_wadah - $jarak_sensor);
        $volume_pakan = abs(floatval($sensor_ultrasonik - $panjang_wadah));
        // $presentase_pakan = round(($volume_pakan / $volume_wadah) * 100);
        $presentase_pakan = round(($volume_pakan / $volume_wadah) * 100, 2);

        // exit(json_encode($presentase_pakan));

        // jika presentase pakan kurang dari 10 persen
        if ($presentase_pakan <= 15) {
            $buzzer = "ON";
        } else {
            $buzzer = "OFF";
        }
        // status servo
        // $status_servo = $this->KontrolModel->select('servo')->orderBy('id', 'desc')->limit(1)->get()->getRowArray()['servo'];
        // CARA 1
        //servo: diinputkan ke tabel sensor jarak (sebagai data lama)
        // control_servo = untuk mengontrol servo 
        $servo = $this->request->getPost("status_servo");
        $servodb = $this->KontrolModel->select('servo')->orderBy('id', 'desc')->limit(1)->get()->getRowArray()['servo'];
        if ($servo != $servodb) {
            // menghidupkan
            $control_servo = $servodb;
        } else {
            // mematikan servo
            $control_servo = 'OFF';
        }

        // INSERT TABEL SUHU
        // $data_suhu = [
        //     'data_sensor' => $sensor_suhu,
        //     'tanggal' => $this->tanggal,
        //     'jam' => $this->jam,
        //     'status_suhu' => $status_suhu,
        //     'penghangat_akuarium' => $penghangat_akuarium,
        // ];

        // // INSERT TABEL JARAK
        // $data_jarak = [
        //     'tanggal' => $this->tanggal,
        //     'jam' => $this->jam,
        //     'data_sensor' => $sensor_ultrasonik,
        //     'buzzer' => $buzzer,
        //     'presentase_pakan' => $presentase_pakan,
        //     'servo' => $status_servo,
        // ];

        // INSERT TABEL SUHU
        $data_suhu = [
            'data_sensor' => $sensor_suhu,
            'tanggal' => $this->tanggal,
            'jam' => $this->jam,
            'status_suhu' => $status_suhu,
            'penghangat_akuarium' => $penghangat_akuarium,
        ];

        // INSERT TABEL JARAK
        $data_jarak = [
            'tanggal' => $this->tanggal,
            'jam' => $this->jam,
            'data_sensor' => $sensor_ultrasonik,
            'buzzer' => $buzzer,
            'presentase_pakan' => $presentase_pakan,
            'servo' => $servo,
        ];

        try {
            // insert suhu
            $this->SensorSuhuModel->insert($data_suhu);
            // insert data sensor
            $this->SensorUltrasonikModel->insert($data_jarak);

            // update to control, jika sudah diberi pakan maka servo otomatis dimatikan dan kosongkan pesan status pakan
            // status servo kita dapatkan dari request yang dikirimkan ke api
            $this->KontrolModel->update($this->id_kontrol, [
                'buzzer' => $buzzer,
                'status_pemberian_pakan' => $status_pemberian_pakan,
                'penghangat_akuarium' => $penghangat_akuarium,
                'servo' => $control_servo,   // kontrol diubah menjadi off agar servo tidak berputar terus
            ]);
            return $this->respond([
                'message' => "Data berhasil ditambahkan"
            ], 200);
        } catch (\Throwable $th) {
            return $this->respond([
                'error' => "Terdapat kesalahan pada sistem!"
            ], 500);
        }
    }

    // public function tambah_suhu()
    // {
    //     $validation = \Config\Services::validation();

    //     $validation->setRule('data_sensor', 'Data sensor suhu', 'required|numeric', [
    //         'required' => "{field} tidak boleh kosong",
    //         'numeric' => "{field} harus berbentuk angka"
    //     ]);

    //     if (!$validation->run($this->request->getPost())) {
    //         return $this->respond([
    //             'errors' => [
    //                 'data_sensor' => $validation->getError('data_sensor')
    //             ]
    //         ], 400);
    //     }

    //     // setting status_suhu dan penghangat akuarium
    //     $data_sensor = $this->request->getPost('data_sensor');
    //     $dataSistem = $this->SistemModel->getKategoriIkan()->getRowArray();
    //     if ($data_sensor >= $dataSistem['suhu_min'] && $data_sensor <= $dataSistem['suhu_max']) {
    //         $status_suhu = "Suhu stabil";
    //         $penghangat_akuarium = "OFF";
    //     } else if ($data_sensor < $dataSistem['suhu_min']) {
    //         $status_suhu = "Akuarium dingin, Suhu untuk " . $dataSistem['kategori'] . " adalah " . $dataSistem['suhu_min'] . "°C s/d " . $dataSistem['suhu_max'] . " °C";
    //         $penghangat_akuarium = "ON";
    //     } else {
    //         $status_suhu = "Akuarium panas, Suhu untuk " . $dataSistem['kategori'] . " adalah " . $dataSistem['suhu_min'] . "°C s/d " . $dataSistem['suhu_max'] . " °C";
    //         $penghangat_akuarium = "OFF";
    //     }

    //     try {
    //         $data = [
    //             'data_sensor' => $data_sensor,
    //             'tanggal' => $this->tanggal,
    //             'jam' => $this->jam,
    //             'status_suhu' => $status_suhu,
    //             'penghangat_akuarium' => $penghangat_akuarium,
    //         ];
    //         // insert suhu
    //         $this->SensorSuhuModel->insert($data);
    //         // update kontrol
    //         $this->KontrolModel->update($this->id_kontrol, [
    //             'penghangat_akuarium' => $penghangat_akuarium
    //         ]);
    //         return $this->respond([
    //             'message' => "Data berhasil ditambahkan"
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return $this->respond([
    //             'error' => "Terdapat kesalahan pada sistem"
    //         ], 500);
    //     }
    // }

    // public function tambah_pakan()
    // {
    //     $validation = \Config\Services::validation();

    //     $validation->setRule('data_sensor', 'Data sensor ultrasonik', 'required|numeric', [
    //         'required' => "{field} tidak boleh kosong",
    //         'numeric' => "{field} harus berbentuk angka"
    //     ]);

    //     if (!$validation->run($this->request->getPost())) {
    //         return $this->respond([
    //             'errors' => [
    //                 'data_sensor' => $validation->getError('data_sensor')
    //             ]
    //         ], 400);
    //     }

    //     // get input post
    //     $data_sensor = (float) $this->request->getPost('data_sensor');
    //     $status_pemberian_pakan = $this->request->getPost("status_pemberian_pakan");

    //     // mengambil data tabel sistem panjang wadah dan jarak antar wadah ke sensor
    //     $dataSistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    //     $panjang_wadah = $dataSistem['panjang_wadah'];
    //     $jarak_sensor = $dataSistem['jarak_sensor'];

    //     // echo $panjang_wadah . ", " . $jarak_sensor;
    //     // die();

    //     $presentase_pakan = round((abs($data_sensor - $panjang_wadah) / ($panjang_wadah - $jarak_sensor)) * 100);
    //     // jika presentase pakan kurang dari 10 persen
    //     if ($presentase_pakan <= 10) {
    //         $buzzer = "ON";
    //     } else {
    //         $buzzer = "OFF";
    //     }

    //     try {
    //         // HAL BEGO YANG TERPIKIRKAN OLEH SAYA
    //         // $servo = $this->request->getPost("servo");
    //         // // JIKA DATA SERVO dari input API yaitu OFF dan dari database adalah ON atau bisa disebut nilai input servo dengan data servo di tabel kontrol berbeda
    //         // $servodb = $this->KontrolModel->select('servo')->orderBy('id', 'desc')->limit(1)->get()->getRowArray()['servo'];
    //         // // jika data servo input dengan dari database tidak sama maka, 
    //         // // $status servo == data dari database
    //         // if ($servo != $servodb) {
    //         //     $status_servo = $servodb;
    //         // } else {
    //         //     $status_servo = $servo;
    //         // }

    //         // HAL BARU YANG LEBIH OK
    //         // MENGAMBIL PERINTAH DARI CONTROL AKUARIUM
    //         $status_servo = $this->request->getPost("status_servo");
    //         $servodb = $this->KontrolModel->select('servo')->orderBy('id', 'desc')->limit(1)->get()->getRowArray()['servo'];
    //         if ($status_servo != $servodb) {
    //             // menghidupkan
    //             $control_servo = $servodb;
    //         } else {
    //             // mematikan
    //             $control_servo = 'OFF';
    //         }

    //         $data = [
    //             'tanggal' => $this->tanggal,
    //             'jam' => $this->jam,
    //             'data_sensor' => $data_sensor,
    //             'buzzer' => $buzzer,
    //             'presentase_pakan' => $presentase_pakan,
    //             'servo' => $status_servo,
    //         ];

    //         // insert data sensor
    //         $this->SensorUltrasonikModel->insert($data);

    //         // update to control, jika sudah diberi pakan maka servo otomatis dimatikan dan kosongkan pesan status pakan
    //         // status servo kita dapatkan dari request yang dikirimkan ke api
    //         $this->KontrolModel->update($this->id_kontrol, [
    //             'buzzer' => $buzzer,
    //             'status_pemberian_pakan' => $status_pemberian_pakan,
    //             'servo' => $control_servo,   // kontrol diubah menjadi off agar servo tidak berputar terus
    //         ]);

    //         return $this->respond([
    //             'message' => "Data berhasil ditambahkan"
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return $this->respond([
    //             'error' => "Terdapat kesalahan pada sistem"
    //         ], 500);
    //     }
    // }
}
