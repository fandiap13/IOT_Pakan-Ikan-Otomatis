<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriIkanModel;
use App\Models\SistemModel;

class Setting extends BaseController
{
    protected $SistemModel;
    protected $KategoriIkanModel;

    public function __construct()
    {
        $this->SistemModel = new SistemModel();
        $this->KategoriIkanModel = new KategoriIkanModel();
    }

    public function index()
    {
        $sistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();

        $kategori_ikan = $this->KategoriIkanModel->findAll();

        if (!$this->request->getPost()) {
            return view('v_setting', [
                'title' => "Setting",
                'subtitle' => "",
                'sistem' => $sistem,
                'kategori_ikan' => $kategori_ikan
            ]);
        }

        try {
            $this->SistemModel->update($sistem['id'], $this->request->getPost());
            session()->setFlashdata('msg', 'success#Data berhasil diubah');
        } catch (\Throwable $th) {
            session()->setFlashdata('msg', 'error#Gagal diubah');
        }
        return redirect()->to(base_url('setting'));
    }

    public function jenis_ikan()
    {
        $jenis_ikan = $this->request->getPost('jenis_ikan');
        $cek_ikan = $this->KategoriIkanModel->find($jenis_ikan);
        $id_sistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray()['id'];

        // dd($cek_ikan);
        if (!$cek_ikan) {
            session()->setFlashdata('msg', 'error#Kategori ikan tidak ditemukan');
            return redirect()->to(base_url('setting'));
        }

        try {
            $this->SistemModel->update($id_sistem, [
                'id_kategori_ikan' => $jenis_ikan
            ]);
            session()->setFlashdata('msg', 'success#Data berhasil diubah !');
        } catch (\Throwable $th) {
            session()->setFlashdata('msg', 'error#Terdapat kesalahan pada sistem !');
        }
        return redirect()->to(base_url('setting'));
    }

    public function tambah_kategori_ikan()
    {
        try {
            $this->KategoriIkanModel->insert($this->request->getPost());
            session()->setFlashdata('msg', 'success#Data berhasil disimpan');
        } catch (\Throwable $th) {
            session()->setFlashdata('msg', 'error#Terdapat kesalahan pada sistem !');
        }
        return redirect()->to(base_url('setting'));
    }

    public function hapus_kategori()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            try {
                $this->KategoriIkanModel->delete($id);
                $json = [
                    'success' => 'Kategori ikan berhasil dihapus'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function modaleditkategori()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $kategori = $this->KategoriIkanModel->find($id);
            $json = [
                'data' => view('modaleditkategori', [
                    'kategori' => $kategori
                ])
            ];
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function edit_kategori_ikan()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            // $kategori = $this->request->getPost('kategori');
            // $suhu_min = $this->request->getPost('suhu_min');
            // $suhu_max = $this->request->getPost('suhu_max');

            try {
                $this->KategoriIkanModel->update($id, $this->request->getPost());
                $json = [
                    'success' => 'Kategori ikan berhasil diubah'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function durasi_pakan()
    {
        $id_sistem = $this->SistemModel->orderBy('id', 'DESC')->limit(1)->get()->getRowArray()['id'];
        $durasi_pakan = $this->request->getPost('durasi_pakan');

        $this->SistemModel->update($id_sistem, [
            'durasi_pakan' => $durasi_pakan
        ]);

        session()->setFlashdata('msg', 'success#Data berhasil disimpan');
        return redirect()->to(base_url('setting'));
    }
}
