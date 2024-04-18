<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class EmployeeActivity implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Fix Bug แก้ไขปัญหากรณีพนักงานถูกลบไอดีแล้ว ให้ออกจากระบบทันที
        $EmployeeModel = new \App\Models\EmployeeModel();
        $employee = $EmployeeModel->getEmployeeByID(session()->get('employeeID'));

        if (!$employee) {
            session()->setFlashdata(['session_expired' => 'เซ็นซันหมดอายุ กรุณาล็อคอินอีกครั้ง']);
            return redirect()->to('/');
        }

        if (session()->get('isEmployeeLoggedIn')) {
            $EmployeeLoginDetailModel = new \App\Models\EmployeeLoginDetailModel();
            date_default_timezone_set("Asia/Bangkok");
            $EmployeeLoginDetailModel->updateEmployeeLoginDetailByID(session()->get('login_detail_id'), ['last_activity' => date('Y-m-d H:i:s')]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}