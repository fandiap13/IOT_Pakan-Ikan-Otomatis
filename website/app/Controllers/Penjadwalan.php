<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjadwalanModel;

class Penjadwalan extends BaseController
{
    protected $PenjadwalanModel;

    public function __construct()
    {
        $this->PenjadwalanModel = new PenjadwalanModel();
    }
    public function index()
    {
        return view("v_penjadwalan", [
            'title' => 'Penjadwalan Pakan',
            'subtitle' => "",
            'jadwal' => $this->PenjadwalanModel->findAll()
        ]);
    }

    public function tambah_penjadwalan()
    {
        if (!$this->request->isAJAX()) {
            exit("Tidak dapat diproses!");
        }

        $jam = $this->request->getPost('jam');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'jam' => [
                'label' => 'Jam',
                'rules' => "required|is_unique[tb_penjadwalan.jam]",
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'is_unique' => '{field} ' . $jam . ' sudah terdaftar di jadwal!'
                ]
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $json = [
                'errors' => [
                    'jam' => $validation->getError('jam'),
                ]
            ];
            exit(json_encode($json));
        }

        $this->PenjadwalanModel->insert([
            'jam' => $jam,
        ]);

        $json = [
            'success' => "Jadwal berhasil ditambahkan"
        ];

        echo json_encode($json);
    }

    public function hapus()
    {
        if (!$this->request->isAJAX()) {
            exit("Tidak dapat diproses!");
        }

        $id = $this->request->getPost('id');
        $cekJadwal = $this->PenjadwalanModel->find($id);
        if (!$cekJadwal) {
            exit(json_encode([
                'error' => "Jadwal tidak ditemukan"
            ]));
        }

        $this->PenjadwalanModel->delete($id);

        echo json_encode([
            'success' => "Jadwal berhasil dihapus"
        ]);
    }

    public function modaleditjadwal()
    {
        if (!$this->request->isAJAX()) {
            exit("Tidak dapat diproses!");
        }

        $id = $this->request->getPost('id');
        $cekJadwal = $this->PenjadwalanModel->find($id);
        if (!$cekJadwal) {
            exit(json_encode([
                'error' => "Jadwal tidak ditemukan"
            ]));
        }

        $json = [
            'data' => view('modaleditjadwal', [
                'jadwal' => $cekJadwal
            ])
        ];

        echo json_encode($json);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) {
            exit("Tidak dapat diproses!");
        }

        $id = $this->request->getPost('id');
        $jam = $this->request->getPost('jam');

        $cekJadwal = $this->PenjadwalanModel->find($id);
        if (!$cekJadwal) {
            exit(json_encode([
                'error' => "Jadwal tidak ditemukan"
            ]));
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'jam' => [
                'label' => 'Jam',
                'rules' => "required|is_unique[tb_penjadwalan.jam,id," . $id . "]",
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'is_unique' => '{field} ' . $jam . ' sudah terdaftar di jadwal!'
                ]
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $json = [
                'errors' => [
                    'jam' => $validation->getError('jam'),
                ]
            ];
            exit(json_encode($json));
        }

        $this->PenjadwalanModel->update($id, [
            'jam' => $jam,
        ]);

        echo json_encode([
            'success' => "Jadwal berhasil diubah"
        ]);
    }
}
