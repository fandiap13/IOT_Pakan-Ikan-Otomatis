<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Login extends BaseController
{
    public function index()
    {
        $UsersModel = new UsersModel();
        if ($this->request->getPost()) {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => [
                    'label' => 'E-mail',
                    'rules' => 'required|valid_email|max_length[100]',
                    'errors' => [
                        'required' => "{field} tidak boleh kosong",
                        'max_length' => "{field} maksimal 100 karakter",
                        'valid_email' => '{field} tidak valid'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|max_length[100]',
                    'errors' => [
                        'required' => "{field} tidak boleh kosong",
                        'max_length' => "{field} maksimal 100 karakter",
                    ]
                ]
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->to(base_url('login'))->withInput();
            }

            $cekUser = $UsersModel->where('email', $this->request->getPost('email'))->get()->getRowArray();
            if (!$cekUser) {
                session()->setFlashdata('msg', 'error#Email tidak terdaftar pada sistem!');
                return redirect()->to(base_url('login'));
            }

            $password = $this->request->getPost('password');
            if (password_verify("$password", $cekUser['password'])) {
                $data_session = [
                    'email' => $cekUser['email'],
                    'nama' => $cekUser['nama'],
                    'time' => date('Y-m-d H:i:s'),
                    'userid' => $cekUser['id'],
                    'login' => true,
                ];
                session()->set("LoginUser", $data_session);
                session()->setFlashdata('msg', 'success#Selamat datang ' . $cekUser['nama']);
                return redirect()->to(base_url('/dashboard'));
            } else {
                session()->setFlashdata('msg', 'error#Password salah!');
                return redirect()->to(base_url('login'));
            }
        }
        return view('v_login');
    }
}
