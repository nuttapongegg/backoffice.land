<?php

namespace App\Controllers;
use App\Models\EmployeeSettingStatusModel;

class Authentication extends BaseController
{

    public function login()
    {
        $status = 500;
        $response['success'] = 0;
        $response['message'] = '';

        try {

            if ($this->request->getMethod() != 'post') throw new \Exception('Invalid Credentials.');

            $EmployeeModel = new \App\Models\EmployeeModel();
            $EmployeeLoginDetailModel = new \App\Models\EmployeeLoginDetailModel();

            $requestPayload = $this->request->getJSON();
            $username = $requestPayload->username ?? null;
            $password = $requestPayload->password ?? null;

            if (!$username || !$password) throw new \Exception('กรุณาตรวจสอบ username หรือ password ของท่าน');

            $employees = $EmployeeModel->getEmployee($username);

            if ($employees) {

                foreach ($employees as $employee) {

                    if ($employee->login_fail < 5) {

                        if (password_verify($password, $employee->password)) {

                            $EmployeeModel->updateEmployeeByID($employee->id, ['login_fail' => 0]);

                            $employeeloginDetailID = $EmployeeLoginDetailModel->insertEmployeeLoginDetail([
                                'employee_id' => $employee->id
                            ]);

                            session()->set([
                                'employeeID' => $employee->id,
                                'username' => $employee->username,
                                'employee_fullname' => $employee->name, // ชื่อพนักงาน
                                'employee_nickname' => $employee->nickname,
                                'positionID' => $employee->position_id,
                                'thumbnail' => $employee->thumbnail,
                                'isEmployeeLoggedIn' => true,
                                'login_detail_id' => $employeeloginDetailID,
                            ]);

                            session()->setFlashdata('announce', true);

                            //pusher Login
                            $pusher = getPusher();
                            $pusher->trigger('color_Status', 'event', [
                                'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                                'event' => 'status_Green',
                                'title' => $employee->username . " : " . 'เข้าสู่ระบบ Backoffice'
                            ]);

                            logger_store([
                                'employee_id' => $employee->id,
                                'username' => $employee->username,
                                'event' => 'เข้าสู่ระบบ',
                                'detail' => 'เข้าสู่ระบบ Backoffice',
                                'ip' => $this->request->getIPAddress()
                            ]);

                            $status = 200;
                            $response['success'] = 1;
                            $response['message'] = 'เข้าสู่ระบบสำเร็จ';

                            if ($employee->position_id == 0) {
                                $response['redirect_to'] = base_url('/finx/list');
                            } else {
                                $response['redirect_to'] = base_url('/loan/list');
                            }
                        } else {
                            $missedTotal = $employee->login_fail + 1;
                            $EmployeeModel->updateEmployeeByID($employee->id, ['login_fail' => $missedTotal]);
                            throw new \Exception('กรุณาตรวจสอบ username หรือ password ของท่าน ' . "$missedTotal/5");
                        }
                    } else {
                        throw new \Exception('User ของท่านถูกล็อค');
                    }
                }
            } else {
                throw new \Exception('กรุณาตรวจสอบ username หรือ password ของท่าน');
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setJSON($response);
    }

    public function logout()
    {
        try {
            $EmployeeLoginDetailModel = new \App\Models\EmployeeLoginDetailModel();
            $EmployeeLoginDetailModel->updateEmployeeLoginDetailByID(session()->get('login_detail_id'), ['active' => '0']);

            // pusherAdd
            $pusher = getPusher();
            $pusher->trigger('color_Status', 'event', [
                'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                'event' => 'status_Red',
                'title' => session()->get('username') . " : " . 'ออกจากระบบ Backoffice'
            ]);

            logger_store([
                'employee_id' => session()->get('employeeID'),
                'username' => session()->get('username'),
                'event' => 'ออกจากระบบ',
                'detail' => 'ออกจากระบบ Backoffice',
                'ip' => $this->request->getIPAddress()
            ]);

            session()->destroy();

            return redirect()->to('/');
        } catch (\Exception $e) {
            //            echo $e->getMessage();
        }
    }
}
