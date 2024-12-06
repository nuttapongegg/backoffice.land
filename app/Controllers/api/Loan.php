<?php

namespace App\Controllers\api;

date_default_timezone_set('Asia/Jakarta');

use App\Controllers\BaseController;

class Loan extends BaseController
{
    public function __construct()
    {
        /*
        | -------------------------------------------------------------------------
        | SET ENVIRONMENT
        | -------------------------------------------------------------------------
        */

        /*
        | -------------------------------------------------------------------------
        | SET UTILITIES
        | -------------------------------------------------------------------------
        */

        // Model
    }

    public function ajaxDataTablePayment($id)
    {
        header('Access-Control-Allow-Origin: http://localhost:8080');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // HANDLE
            $data = [1];

            $response['data'] = $data;
            $response['message'] = 'success';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);

        } catch (\Exception $e) {

            $response['code'] = 500;
            $response['message'] = 'error';
            
            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }
}
