<?php

namespace App\Controllers;

use App\Models\SettingLandModel;
use App\Models\OverdueStatusModel;

class Setting extends BaseController
{
    // index
    public function index()
    {
        $data['js_critical'] = '
            <script src="' . base_url('/assets/app/js/setting/overdue_status/index.js') . '"></script>
            ';
        $data['content'] = 'setting/index';
        $data['title'] = 'ตั้งค่า';
        return view('app', $data);
    }

    // Land
    public function listLand()
    {
        $SettingLandModel = new SettingLandModel();
        $data['setting_lands'] = $SettingLandModel->getSettingLandAll();

        $RealInvestmentModel = new \App\Models\RealInvestmentModel();
        $data['real_investment'] = $RealInvestmentModel->getRealInvestmentAll();

        $data['content'] = 'setting/setting_land/index';
        $data['title'] = 'ตั้งค่าสินเชื่อ';
        $data['js_critical'] = '<script src="' . base_url('/assets/app/js/setting/land/index.js?v=' . time()) . '"></script>';
        return view('app', $data);
    }

    //updateRealInvestment
    public function updateRealInvestment()
    {
        $RealInvestmentModel = new \App\Models\RealInvestmentModel();

        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('RealInvestmentId');

            // HANDLE REQUEST
            $update = $RealInvestmentModel->updateRealInvestmentByID($id, [
                'investment' => $this->request->getVar('editRealInvestment'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขเงินลงทุนจริง'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] เงินลงทุนจริง',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข เงินลงทุนจริง สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข เงินลงทุนจริง ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    // addLandAccount data 
    public function addLandAccount()
    {
        $SettingLandModel = new SettingLandModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $detail = 'เพิ่มบัญชี ' . $this->request->getVar('land_account_name');

            // HANDLE REQUEST
            $create = $SettingLandModel->insertSettingLand([
                'land_account_name' => $this->request->getVar('land_account_name'),
                'land_account_cash' => $this->request->getVar('land_account_cash'),
            ]);
            $SettingLand = $SettingLandModel->getSettingLandByName($this->request->getVar('land_account_name'));

            $SettingLandModel->insertSettingLandLogs([
                'setting_land_id' => $SettingLand->id,
                'setting_land_detail' => $detail,
                'setting_land_money' => $this->request->getVar('land_account_cash'),
                'setting_land_note' => '',
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);

            if ($create) {

                // pusherAdd
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Blue',
                    'title' => session()->get('username') . " : " . 'ทำการเพิ่มบัญชีสินเชื่อ' . " " . $this->request->getVar('land_account_name')
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'เพิ่ม',
                    'detail' => '[เพิ่ม] บัญชีสินเชื่อ',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'เพิ่ม บัญชีสินเชื่อ สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'เพิ่ม บัญชีสินเชื่อ ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    //edit editLandAccount
    public function editLandAccount($id = null)
    {
        $SettingLandModel = new SettingLandModel();
        $data = $SettingLandModel->getSettingLandByID($id);

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }

    //updateLandAccount
    public function updateLandAccount()
    {
        $SettingLandModel = new SettingLandModel();

        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('LandAccountId');
            $detail = 'แก้ไขบัญชี ' . $this->request->getVar('edit_land_account_name');

            // HANDLE REQUEST
            $update = $SettingLandModel->updateSettingLandByID($id, [
                'land_account_name' => $this->request->getVar('edit_land_account_name'),
                'land_account_cash' => $this->request->getVar('edit_land_account_cash'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $SettingLandModel->insertSettingLandLogs([
                'setting_land_id' => $id,
                'setting_land_detail' => $detail,
                'setting_land_money' => $this->request->getVar('edit_land_account_cash'),
                'setting_land_note' => '',
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขบัญชีสินเชื่อ' . "  " . $this->request->getVar('edit_land_account_name')
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] บัญชีสินเชื่อ',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข บัญชีสินเชื่อ สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข บัญชีสินเชื่อ ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    // deleteLandAccount
    public function deleteLandAccount($id = null)
    {
        $SettingLandModel = new SettingLandModel();

        $SettingLandModel->updateSettingLandByID($id, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        //pusher
        $data['land_account_name'] = $SettingLandModel->getSettingLandByID($id);
        $detail = 'ลบบัญชี ' . $data['land_account_name']->land_account_name;
        $SettingLandModel->insertSettingLandLogs([
            'setting_land_id' => $id,
            'setting_land_detail' => $detail,
            'setting_land_money' => $data['land_account_name']->land_account_cash,
            'setting_land_note' => '',
            'employee_id' => session()->get('employeeID'),
            'employee_name' => session()->get('employee_fullname')
        ]);

        // pusherDelete
        $pusher = getPusher();
        $pusher->trigger('color_Status', 'event', [
            'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
            'event' => 'status_Red',
            'title' => session()->get('username') . " : " . 'ทำการลบบัญชีสินเชื่อ' . "  " . $data['land_account_name']->land_account_name
        ]);

        logger_store([
            'employee_id' => session()->get('employeeID'),
            'username' => session()->get('username'),
            'event' => 'ลบ',
            'detail' => '[ลบ] บัญชีสินเชื่อ',
            'ip' => $this->request->getIPAddress()
        ]);
    }

    // addLandAccountPlus data 
    public function addLandAccountPlus()
    {
        $SettingLandModel = new SettingLandModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $detail = 'เพิ่มเงินเข้าบัญชี';
            $employee_id = session()->get('employeeID');
            $employee_name = session()->get('employee_fullname');

            $land_accounts =  $SettingLandModel->getSettingLandAll();
            foreach ($land_accounts as $land_account) {
                if ($this->request->getVar('LandAccountId') == $land_account->id) {
                    $name = $land_account->land_account_name;
                    $price = str_replace(',', '', $this->request->getVar('land_account_money_plus'));
                    $land_account_cash_receipt = $land_account->land_account_cash + floatval($price);

                    $SettingLandModel->updateSettingLandByID($this->request->getVar('LandAccountId'), [
                        'land_account_cash' => $land_account_cash_receipt,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            // HANDLE REQUEST
            $create = $SettingLandModel->insertSettingLandLogs([
                'setting_land_id' => $this->request->getVar('LandAccountId'),
                'setting_land_detail' => $detail,
                'setting_land_money' => $this->request->getVar('land_account_money_plus'),
                'setting_land_note' => $this->request->getVar('land_account_note_plus'),
                'employee_id' => $employee_id,
                'employee_name' => $employee_name
            ]);

            $SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $this->request->getVar('LandAccountId'),
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $this->request->getVar('land_account_money_plus'),
                'setting_land_report_note' => $this->request->getVar('land_account_note_plus'),
                'setting_land_report_account_balance' => $land_account_cash_receipt,
                'employee_id' => $employee_id,
                'employee_name' => $employee_name
            ]);

            if ($create) {

                // pusherAdd
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Blue',
                    'title' => session()->get('username') . " : " . 'ทำการเพิ่มเงินเข้าบัญชี' . " " . $name
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'เพิ่ม',
                    'detail' => '[เพิ่ม] เงินเข้าบัญชี',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'เพิ่ม เงินเข้าบัญชี สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'เพิ่ม เงินเข้าบัญชี ไม่สำเร็จ';
            }
            // print_r($response['success']);
            // exit();
            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    // addLandAccountMinus data 
    public function addLandAccountMinus()
    {
        $SettingLandModel = new SettingLandModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $detail = 'ลบเงินออกจากบัญชี';
            $employee_id = session()->get('employeeID');
            $employee_name = session()->get('employee_fullname');

            $land_accounts =  $SettingLandModel->getSettingLandAll();
            foreach ($land_accounts as $land_account) {
                if ($this->request->getVar('LandAccountId') == $land_account->id) {
                    $name = $land_account->land_account_name;
                    $price = str_replace(',', '', $this->request->getVar('land_account_money_minus'));
                    $land_account_cash_receipt = $land_account->land_account_cash - floatval($price);

                    $SettingLandModel->updateSettingLandByID($this->request->getVar('LandAccountId'), [
                        'land_account_cash' => $land_account_cash_receipt,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            // HANDLE REQUEST
            $create = $SettingLandModel->insertSettingLandLogs([
                'setting_land_id' => $this->request->getVar('LandAccountId'),
                'setting_land_detail' => $detail,
                'setting_land_money' => $this->request->getVar('land_account_money_minus'),
                'setting_land_note' => $this->request->getVar('land_account_note_minus'),
                'employee_id' => $employee_id,
                'employee_name' => $employee_name
            ]);

            $SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $this->request->getVar('LandAccountId'),
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $this->request->getVar('land_account_money_minus'),
                'setting_land_report_note' => $this->request->getVar('land_account_note_minus'),
                'setting_land_report_account_balance' => $land_account_cash_receipt,
                'employee_id' => $employee_id,
                'employee_name' => $employee_name
            ]);

            if ($create) {

                // pusherAdd
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Blue',
                    'title' => session()->get('username') . " : " . 'ทำการลบเงินออกจากบัญชี' . " " . $name
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'ลบ',
                    'detail' => '[ลบ] เงินออกจากบัญชี',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'ลบ เงินออกจากบัญชี สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'ลบ เงินออกจากบัญชี ไม่สำเร็จ';
            }
            // print_r($response['success']);
            // exit();
            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    // getLandAccount
    public function getLandAccount()
    {
        $SettingLandModel = new \App\Models\SettingLandModel();
        $data = $SettingLandModel->getSettingLandAll();
        $json_data = array(
            "data" => $data // total data array
        );
        echo json_encode($json_data);
    }

    // TransferLandAccount data 
    public function TransferLandAccount()
    {
        $SettingLandModel = new SettingLandModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $employee_id = session()->get('employeeID');
            $employee_name = session()->get('employee_fullname');
            // $detail_minus = '';
            // $detail_cash = '';
            $land_accounts =  $SettingLandModel->getSettingLandAll();
            $land_account_cashs =  $SettingLandModel->getSettingLandByID($this->request->getVar('land_account_name'));
            foreach ($land_accounts as $land_account) {
                // print_r($cashflow);
                if ($this->request->getVar('LandAccountId') == $land_account->id) {
                    // px($this->request->getVar('LandAccountId').'ss'.$cashflow->id);
                    $name = $land_account->land_account_name;
                    $price_minus = str_replace(',', '', $this->request->getVar('transfer_money_land_account'));
                    $land_account_minus = $land_account->land_account_cash - floatval($price_minus);

                    $detail_minus = 'โอนเงินไปยัง ' . $land_account_cashs->land_account_name;
                    $detail_cash = 'รับโอนจาก ' . $land_account->land_account_name;
                    $SettingLandModel->updateSettingLandByID($this->request->getVar('LandAccountId'), [
                        'land_account_cash' => $land_account_minus,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    // HANDLE REQUEST
                    $create = $SettingLandModel->insertSettingLandLogs([
                        'setting_land_id' => $this->request->getVar('LandAccountId'),
                        'setting_land_detail' => $detail_minus,
                        'setting_land_money' => $this->request->getVar('transfer_money_land_account'),
                        'setting_land_note' => $this->request->getVar('transfer_land_account_note'),
                        'employee_id' => $employee_id,
                        'employee_name' => $employee_name
                    ]);

                    $SettingLandModel->insertSettingLandReport([
                        'setting_land_id' => $this->request->getVar('LandAccountId'),
                        'setting_land_report_detail' => $detail_minus,
                        'setting_land_report_money' => $this->request->getVar('transfer_money_land_account'),
                        'setting_land_report_note' => $this->request->getVar('transfer_land_account_note'),
                        'setting_land_report_account_balance' => $land_account_minus,
                        'employee_id' => $employee_id,
                        'employee_name' => $employee_name
                    ]);

                    // px($land_account_cashs);
                    if ($this->request->getVar('land_account_name') == $land_account_cashs->id) {
                        $price_plus = str_replace(',', '', $this->request->getVar('transfer_money_land_account'));
                        $land_account_plus = $land_account_cashs->land_account_cash + floatval($price_plus);

                        $SettingLandModel->updateSettingLandByID($this->request->getVar('land_account_name'), [
                            'land_account_cash' => $land_account_plus,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        // px($detail_cash);
                        // HANDLE REQUEST
                        $create = $SettingLandModel->insertSettingLandLogs([
                            'setting_land_id' => $land_account_cashs->id,
                            'setting_land_detail' => $detail_cash,
                            'setting_land_money' => $this->request->getVar('transfer_money_land_account'),
                            'setting_land_note' => $this->request->getVar('transfer_land_account_note'),
                            'employee_id' => $employee_id,
                            'employee_name' => $employee_name
                        ]);

                        $SettingLandModel->insertSettingLandReport([
                            'setting_land_id' => $land_account_cashs->id,
                            'setting_land_report_detail' => $detail_cash,
                            'setting_land_report_money' => $this->request->getVar('transfer_money_land_account'),
                            'setting_land_report_note' => $this->request->getVar('transfer_land_account_note'),
                            'setting_land_report_account_balance' => $land_account_plus,
                            'employee_id' => $employee_id,
                            'employee_name' => $employee_name
                        ]);
                    }
                }
            }

            if ($create) {

                // pusherAdd
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Blue',
                    'title' => session()->get('username') . " : " . 'ทำการโอนเงินจาก ' . $name . ' ไปยัง' . $land_account_cashs->land_account_name
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] โอนเงิน',
                    'ip' => $this->request->getIPAddress()
                ]);

                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'โอนเงิน สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'โอนเงิน ไม่สำเร็จ';
            }
            // print_r($response['success']);
            // exit();
            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function ajaxTablesLandAccountLogs()
    {
        $SettingLandModel = new SettingLandModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $SettingLandModel->getSettingLandLogsSearchcount($param);

            $dataLandAccountLogs = $SettingLandModel->getSettingLandLogsSearch($param);
        } else {
            // count all data
            $total_count = $SettingLandModel->getSettingLandLogsAllCount();

            // get per page data
            $dataLandAccountLogs = $SettingLandModel->getSettingLandLogsAll($param);
        }

        $i = $_POST['start'];
        $data = [];
        foreach ($dataLandAccountLogs as $datas) {
            $i++;

            $data[] = array(
                $i,
                $datas->setting_land_detail,
                $datas->land_account_name,
                number_format($datas->setting_land_money, 2),
                $datas->setting_land_note,
                $datas->employee_name,
                dateThai($datas->created_at_logs)
            );
        }
        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data // total data array
        );
        echo json_encode($json_data);
    }

    public function ajaxTablesLandAccounReport($id = null)
    {
        $SettingLandModel = new SettingLandModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['id'] = $id;

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $SettingLandModel->getSettingLandReportSearchcount($param);

            $dataLandAccountReport = $SettingLandModel->getSettingLandReportSearch($param);
        } else {
            // count all data
            $total_count = $SettingLandModel->getSettingLandReportAllCount($param);

            // get per page data
            $dataLandAccountReport = $SettingLandModel->getSettingLandReportAll($param);
        }

        $i = $_POST['start'];
        $data = [];
        foreach ($dataLandAccountReport as $LandAccountReport) {
            $i++;
            if ($LandAccountReport->setting_land_report_account_balance != '') {
                $setting_land_report_account_balance = number_format($LandAccountReport->setting_land_report_account_balance, 2);
            } else {
                $setting_land_report_account_balance = '-';
            }
            $data[] = array(
                $i,
                $LandAccountReport->setting_land_report_detail,
                $LandAccountReport->land_account_name,
                number_format($LandAccountReport->setting_land_report_money, 2),
                $LandAccountReport->setting_land_report_note,
                $LandAccountReport->employee_name,
                $setting_land_report_account_balance,
                dateThai($LandAccountReport->created_at)
            );
        }
        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data // total data array
        );
        echo json_encode($json_data);
    }

    // show Modal editSettingOverdueStatus
    public function editSettingOverdueStatus()
    {
        $OverdueStatusModel = new OverdueStatusModel();

        if ($OverdueStatusModel->getOverdueStatusAll()) {
            echo json_encode(array("status" => true, 'data' => $OverdueStatusModel->getOverdueStatusAll()));
        } else {
            echo json_encode(array("status" => false));
        }
    }

    // update data
    public function updateSettingOverdueStatus()
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $OverdueStatusModel = new OverdueStatusModel();

            if ($this->request->getVar('checkbox_Token_Loan') != '') {
                $token_loan_status = 1;
            } else {
                $token_loan_status = 0;
            }

            $update = $OverdueStatusModel->updateOverdueStatus([
                'token_loan' => $this->request->getVar('token_Loan'),
                'token_overdue_loan' => $this->request->getVar('overdue_Loan'),
                'token_loan_status' => $token_loan_status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                //pusher edit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขตั้งค่าแจ้งเตือนสินเชื่อ'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] ตั้งค่าแจ้งเตือนสินเชื่อ',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข ตั้งค่าแจ้งเตือนสินเชื่อ สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข ตั้งค่าแจ้งเตือนสินเชื่อ ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
