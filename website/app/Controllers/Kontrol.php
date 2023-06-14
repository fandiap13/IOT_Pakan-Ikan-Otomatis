<?php

// BAGIAN YANG MENGONTROL PEMBERIAN PAKAN

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KontrolModel;
use App\Models\SensorUltrasonikModel;

class Kontrol extends BaseController
{
    protected $KontrolModel;
    protected $SensorUltrasonikModel;

    public function __construct()
    {
        $this->KontrolModel = new KontrolModel();
        $this->SensorUltrasonikModel = new SensorUltrasonikModel();
    }

    public function getDataKontrol()
    {
        if ($this->request->isAJAX()) {
            $data_sensor_jarak = $this->SensorUltrasonikModel->limit(1)
                ->where('servo', "ON")
                ->orderBy('id', 'DESC')->get()->getRowArray();
            $json = [
                'data' => $this->KontrolModel->getDataKontrol()->getRowArray(),
                'tgl_terbaru_pemberian_pakan' => $data_sensor_jarak ? $data_sensor_jarak['tanggal'] . " " . $data_sensor_jarak['jam'] : ""
            ];
            echo json_encode($json);
        } else {
            exit("Maaf tidak dapat diproses");
        }
    }

    public function beri_pakan()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_control');

            // ambil data sensor ultrasonik
            $ultrasonic_update = $this->SensorUltrasonikModel->getData()->getRowArray();
            $presentase_pakan = $ultrasonic_update['presentase_pakan'];

            // if ($presentase_pakan > 10 && $presentase_pakan <= 100) {
            if ($presentase_pakan > 15) {
                $servo = "ON";
                $status_pemberian_pakan = null; // status berhasil akan ditangkap dari bergeraknya alat
            } else {
                $servo = "OFF";
                $status_pemberian_pakan = "Pemberian pakan Gagal! sisa pakan " . $presentase_pakan . " %";
            }

            try {
                $this->KontrolModel->update($id, [
                    'servo' => $servo,
                    'status_pemberian_pakan' => $status_pemberian_pakan
                ]);
                echo json_encode([
                    'success' => "Perintah berhasil dijalankan"
                ]);
            } catch (\Throwable $th) {
                $json = [
                    'error' => "Terdapat kesalahan pada sistem"
                ];
                echo json_encode($json);
            }
        } else {
            exit("Maaf tidak dapat diproses");
        }
    }
}
