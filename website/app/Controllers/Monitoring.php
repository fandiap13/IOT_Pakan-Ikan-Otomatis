<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    public function index()
    {
        return view('v_monitoring', [
            'title' => 'Monitoring',
            'subtitle' => ""
        ]);
    }
}
