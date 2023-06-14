<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function index()
    {
        session()->remove('LoginUser');
        session()->setFlashdata('msg', 'success#Anda berhasil logout');
        return redirect()->to(base_url('login'));
    }
}
