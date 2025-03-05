<?php

namespace App\Controllers;

class Map extends BaseController
{
    public function index()
    {
        $data['content'] = 'loan/map';
        $data['title'] = 'Map';
        $data['css_critical'] = '';
        $data['js_critical'] = ' ';

        echo view('/app', $data);
    }
}
