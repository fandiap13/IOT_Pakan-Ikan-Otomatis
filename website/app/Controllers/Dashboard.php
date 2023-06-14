<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userid = session()->get('LoginUser')['userid'];
        $modelUser = new UsersModel();

        if ($this->request->getPost()) {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => [
                    'label' => 'Email',
                    'rules' => "required|max_length[100]|valid_email|is_unique[tb_users.email,id," . $userid . "]",
                    'errors' => [
                        'required' => "{field} tidak boleh kosong!",
                        'valid_email' => "{field} tidak valid!",
                        'is_unique' => "{field} sudah digunakan!",
                        'max_length' => "{field} maksimal berisi 100 karakter"
                    ]
                ],
                'nama' => [
                    'label' => 'Nama',
                    'rules' => "required|max_length[100]",
                    'errors' => [
                        'required' => "{field} tidak boleh kosong!",
                        'max_length' => "{field} maksimal berisi 100 karakter"
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => "required|max_length[100]|matches[retype_password]",
                    'errors' => [
                        'required' => "{field} tidak boleh kosong!",
                        'matches' => "Retype password tidak sama!",
                        'max_length' => "{field} maksimal berisi 100 karakter"
                    ]
                ]
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->to(base_url('dashboard'))->withInput();
            }

            $data = $this->request->getPost();
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['password'] = $password_hash;

            try {
                $modelUser->update($userid, $data);
                session()->setFlashdata('msg', 'success#Profil berhasi diubah');
                return redirect()->to(base_url('dashboard'));
            } catch (\Throwable $th) {
                session()->setFlashdata('msg', 'error#Terdapat kesalahan pada sistem!');
                return redirect()->to(base_url('dashboard'));
            }
        }

        return view('v_dashboard', [
            'title' => 'Dashboard',
            'subtitle' => "",
            'userData' => $modelUser->find($userid)
        ]);
    }
}
