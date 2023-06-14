<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Users extends BaseController
{
    protected $UsersModel;

    public function __construct()
    {
        $this->UsersModel = new UsersModel();
    }

    public function index()
    {
        return view("v_users", [
            'title' => 'Users',
            'subtitle' => '',
            'users' => $this->UsersModel->findAll()
        ]);
    }

    public function tambah()
    {
        if (!$this->request->isAJAX()) {
            exit(json_encode("Tidak dapat diproses!"));
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'label' => "E-mail",
                'rules' => "required|valid_email|is_unique[tb_users.email]|max_length[100]",
                'errors' => [
                    'required' => "{field} tidak boleh kosong!",
                    'valid_email' => "{field} tidak valid!",
                    'is_unique' => "{field} sudah digunakan!",
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'nama' => [
                'label' => "Nama lengkap",
                'rules' => "required|max_length[100]",
                'errors' => [
                    'required' => "{field} tidak boleh kosong!",
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'password' => [
                'label' => "Password",
                'rules' => "required|max_length[100]",
                'errors' => [
                    'required' => "{field} tidak boleh kosong!",
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'retype_password' => [
                'label' => "Retype password",
                'rules' => "matches[password]",
                'errors' => [
                    'matches' => "{field} tidak valid!",
                ]
            ],
        ]);

        if (!$validation->run($this->request->getPost())) {
            $json = [
                'errors' => [
                    'email' => $validation->getError('email'),
                    'nama' => $validation->getError('nama'),
                    'password' => $validation->getError('password'),
                    'retype_password' => $validation->getError('retype_password'),
                ]
            ];
            exit(json_encode($json));
        }

        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->UsersModel->insert($data);

        $json = [
            'success' => 'User berhasil ditambahkan'
        ];
        echo json_encode($json);
    }

    public function edit($id)
    {
        if (!$this->request->isAJAX()) {
            exit(json_encode("Tidak dapat diproses!"));
        }

        $cekUser = $this->UsersModel->find($id);
        if (!$cekUser) {
            exit(json_encode([
                'error' => "User tidak ditemukan!"
            ]));
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'label' => "E-mail",
                'rules' => "required|valid_email|is_unique[tb_users.email, id," . $id . "]|max_length[100]",
                'errors' => [
                    'required' => "{field} tidak boleh kosong!",
                    'valid_email' => "{field} tidak valid!",
                    'is_unique' => "{field} sudah digunakan!",
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'nama' => [
                'label' => "Nama lengkap",
                'rules' => "required|max_length[100]",
                'errors' => [
                    'required' => "{field} tidak boleh kosong!",
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'password' => [
                'label' => "Password",
                'rules' => "permit_empty|max_length[100]",
                'errors' => [
                    'max_length' => "{field} tidak boleh lebih dari 100 karakter!",
                ]
            ],
            'retype_password' => [
                'label' => "Retype password",
                'rules' => "matches[password]",
                'errors' => [
                    'matches' => "{field} tidak valid!",
                ]
            ],
        ]);

        if (!$validation->run($this->request->getPost())) {
            $json = [
                'errors' => [
                    'email' => $validation->getError('email'),
                    'nama' => $validation->getError('nama'),
                    'password' => $validation->getError('password'),
                    'retype_password' => $validation->getError('retype_password'),
                ]
            ];
            exit(json_encode($json));
        }

        $data = $this->request->getPost();
        if ($this->request->getPost('password') == "" || $this->request->getPost('password') == null) {
            $data['password'] = $cekUser['password'];
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->UsersModel->update($id, $data);

        $json = [
            'success' => 'User berhasil diubah'
        ];
        echo json_encode($json);
    }

    public function hapus($id)
    {
        $cekUser = $this->UsersModel->find($id);
        if (!$cekUser) {
            session()->setFlashdata('msg', "error#User tidak ditemukan!");
            return redirect()->to(base_url("users"));
        }

        $this->UsersModel->delete($id);

        session()->setFlashdata('msg', "success#User berhasil dihapus!");
        return redirect()->to(base_url("users"));
    }
}
