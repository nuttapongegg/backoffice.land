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

    public function ajaxDataTableLandPayment($month, $years)
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllCreated($month, $years);

            // กำหนดตัวแปร HTML ที่จะเก็บข้อมูล
            $html = '';
            $html .= '
                <table class="table table-bordered table-striped">
                    <tbody>                   
                        <tr>
                            <td><h6 class="text-center p-2 mb-0">วันที่</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ยอดวงเงินกู้รวม</h6></td>
                            <td><h6 class="text-center p-2 mb-0">เงินสดในบัญชี</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ดอกเบี้ยที่เก็บแล้ว</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ทรัพย์สินสุทธิ</h6></td>
                        </tr>
            ';

            foreach ($dataLandLogs as $dataLandLog) {
                $html .= '          
                        <tr>
                            <td><h6 class="text-center p-2 mb-0">' . $dataLandLog->formatted_date . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_loan_amount, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_cash_flow, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_interest, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_summary_net, 2) . '</h6></td>
                        </tr>
            ';
            }

            $html .= '</tbody></table>';

            $response['data'] = $html;
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

    public function ajaxDataTableLandPaymentToDay()
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllCreatedToDay();
            $land_logs_cash_flow = 0;
            $land_logs_cash_flow = $dataLandLogs->land_logs_cash_flow;
            $json_data = array(
                "LandLord_S" => number_format($land_logs_cash_flow, 2, '.', '')
            );

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($json_data);
        } catch (\Exception $e) {

            $response['code'] = 500;
            $response['message'] = 'error';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }

    public function ajaxDataTableLandDay($days)
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllByDay($days);
            // $land_logs_cash_flow = 0;
            // $land_logs_cash_flow = $dataLandLogs->land_logs_cash_flow;
            // $json_data = array(
            //     "LandLord_S" => number_format($land_logs_cash_flow, 2, '.', '')
            // );
            $dates = [];
            for ($i = $days; $i > 0; $i--) {
                $dates[] = date("d M Y", strtotime("-$i days"));
            }
            $LandLord = [];
            foreach ($dates as $date) {
                $LandLord[$date] = 0;
            }

            // ประมวลผลข้อมูลจากฐานข้อมูล
            foreach ($dataLandLogs as $row) {
                $date = date("d M Y", strtotime($row->formatted_date));

                // กำหนดค่าที่ได้รับจากฐานข้อมูล (ตัวอย่างเช่น กำหนดให้บัญชี EVX)
                $LandLord[$date] = number_format($row->land_logs_summary_net, 2, '.', '');
            }

            // ส่งผลลัพธ์ในรูปแบบ JSON
            $json_data = array(
                "LandLord" => array_values($LandLord)
            );

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($json_data);
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
