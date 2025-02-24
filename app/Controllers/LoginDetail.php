<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\EmployeeLoginDetailModel;
use App\Models\EmployeeModel;

class LoginDetail extends BaseController
{
    private EmployeeLoginDetailModel $EmployeeLoginDetailModel;
    private EmployeeModel $EmployeeModel;

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
        $this->EmployeeLoginDetailModel = new EmployeeLoginDetailModel();
        $this->EmployeeModel = new EmployeeModel();
    }

    public function index()
    {
        // SET CONFIG
        $status = 500;
        $response['success'] = 0;
        $response['message'] = '';
        $response['data']['html'] = '';

        // HANDLE REQUEST
        $employees = $this->EmployeeModel->getEmployeeAll();

        foreach ($employees as $employee) {

            // ไม่ใช่ ID ตัวเอง
            if ($employee->id != session()->get('employeeID')) {

                $employeeOnline = $this->EmployeeLoginDetailModel->getEmployeeOnline($employee->id);

                // กรณี: Online
                if ($employeeOnline) {

                    $textOnline = 'กำลังใช้งานอยู่';

//                    $response['data']['html'] .= '
//                        <div class="symbol symbol-circle symbol-45px" title="' . $employee->username . '(' . $employee->nickname . ')' . ' | ' . $textOnline . '">
//                            <img src="' . base_url('assets/images/avtar/' . $employee->img . '.png') . '" class="rounded-circle">
//                        </div>
//                    ';

                    $response['data']['html'] .= '
                        <div style="margin-right: 4px;" class="symbol symbol-circle symbol-45px avatar avatar-indicators avatar-online" title="' . $employee->username . '(' . $employee->nickname . ')' . ' | ' . $textOnline . '">
                            <img src="' . base_url('assets/images/avtar/' . $employee->img . '.png') . '" class="rounded-circle">
                        </div>
                    ';
                }

                // กรณี: Offline
                else {

                    $textOffline = '';
                    $employeeOffline = $this->EmployeeLoginDetailModel->getEmployeeOffline($employee->id);
                    $class = '';

                    if ($employeeOffline) {
                        $textOffline = 'ออฟไลน์ ' . datetime_compare($employeeOffline->last_activity);
                        // $class = 'd-none d-sm-inline-block';
                        $class = 'd-none';
                    } else {
                        $textOffline = 'ออฟไลน์';
                        // $class = 'd-none d-sm-inline-block';
                        $class = 'd-none';
                    }

//                    $response['data']['html'] .= '
//                        <div class="symbol symbol-circle symbol-45px ' . $class . '" title="' . $employee->username . '(' . $employee->nickname . ')' . ' | ' . $textOffline . '">
//                            <img src="' . base_url('assets/images/avtar/' . $employee->img . '.png') . '" class="rounded-circle">
//                        </div>
//                    ';

                    $response['data']['html'] .= '
                        <div style="margin-right: 4px;" class="symbol symbol-circle symbol-45px avatar avatar-indicators avatar-offline ' . $class . '" title="' . $employee->username . '(' . $employee->nickname . ')' . ' | ' . $textOffline . '">
                            <img src="' . base_url('assets/images/avtar/' . $employee->img . '.png') . '" class="rounded-circle">
                        </div>
                    ';
                }
            }
        }

        $status = 200;
        $response['success'] = 1;
        $response['message'] = '';

        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setJSON($response);
    }
}