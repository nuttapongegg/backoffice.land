<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class EmployeeAuth implements FilterInterface
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

        if (!session()->get('isEmployeeLoggedIn')) return redirect()->to('/');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}